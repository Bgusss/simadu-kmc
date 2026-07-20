const { chromium } = require('playwright');
const fs            = require('fs');
const path          = require('path');

/**
 * Normalisasi URL komentar:
 * Hapus query param dinamis Facebook (?__cft__, &__tn__, dll.)
 * sehingga URL yang sama tidak dianggap berbeda antar-run.
 */
function normalizeCommentUrl(href) {
    try {
        const url          = new URL(href);
        const stableParams = ['story_fbid', 'id', 'comment_id', 'reply_comment_id'];
        const normalized   = new URL(url.origin + url.pathname);

        stableParams.forEach(key => {
            if (url.searchParams.has(key)) {
                normalized.searchParams.set(key, url.searchParams.get(key));
            }
        });

        return normalized.toString();
    } catch {
        return href;
    }
}

/**
 * Baca daftar comment_id & comment_link yang sudah ada di DB.
 * File ini dibuat oleh PHP sebelum menjalankan script ini.
 */
function loadKnownIds() {
    const filePath = path.join(__dirname, 'known-comment-ids.json');

    if (!fs.existsSync(filePath)) {
        console.log('File known-comment-ids.json tidak ditemukan, semua dianggap baru.');
        return { ids: new Set(), links: new Set() };
    }

    try {
        const data  = JSON.parse(fs.readFileSync(filePath, 'utf-8'));
        const ids   = new Set(data.map(r => String(r.comment_id)).filter(Boolean));
        const links = new Set(data.map(r => normalizeCommentUrl(r.comment_link)).filter(Boolean));

        console.log(`Known IDs dimuat: ${ids.size} comment_id, ${links.size} comment_link.`);
        return { ids, links };
    } catch (e) {
        console.log('Gagal membaca known-comment-ids.json: ' + e.message);
        return { ids: new Set(), links: new Set() };
    }
}

(async () => {

    /*
     * Muat daftar notifikasi yang sudah ada di DB
     */
    const known = loadKnownIds();

    const browser = await chromium.launch({
        headless: false,
        args: [
            '--disable-features=Translate',
            '--disable-translate',
            '--disable-extensions',
            '--lang=id-ID'
        ]
    });

    const context = await browser.newContext({
        storageState: 'facebook-session.json',
        locale: 'id-ID',
        timezoneId: 'Asia/Pontianak',
        extraHTTPHeaders: {
            'Accept-Language': 'id-ID,id;q=0.9'
        }
    });
    
    // Inject script untuk disable auto-translate di halaman
    await context.addInitScript(() => {
        // Override Chrome translate
        Object.defineProperty(navigator, 'languages', {
            get: () => ['id-ID', 'id']
        });
        Object.defineProperty(navigator, 'language', {
            get: () => 'id-ID'
        });
    });

    const page = await context.newPage();

    const openOriginalText = async (targetPage) => {
        const originalButtons = targetPage.locator(
            'text=/Lihat Asli|See Original|View Original/i'
        );

        const count = await originalButtons.count();
        if (count === 0) {
            return;
        }

        for (let i = 0; i < count; i++) {
            try {
                await originalButtons.nth(i).click({ timeout: 1500 });
                await targetPage.waitForTimeout(500);
            } catch {
                // Abaikan tombol yang tidak visible/terlepas karena DOM Facebook berubah.
            }
        }

        await targetPage.waitForTimeout(1000);
    };

    const extractSenderName = (text) => {
        return (text || '')
            .replace(/\s+/g, ' ')
            .replace(/\s+(menyebut Anda|mentioned you|mentioned Anda).*$/i, '')
            .replace(/\s+(dalam sebuah komentar|dalam komentar|in a comment).*$/i, '')
            .trim();
    };

    console.log('Membuka notifikasi Facebook...');

    await page.goto(
        'https://www.facebook.com/notifications',
        { waitUntil: 'domcontentloaded', timeout: 45000 }
    );

    await page.waitForTimeout(5000);

    console.log('Mengambil link agregasi mention...');

    const aggregateLinks = await page
        .locator('a')
        .evaluateAll(elements => {
            return elements
                .map(el => ({
                    text: el.innerText ? el.innerText.trim() : '',
                    href: el.href || ''
                }))
                .filter(item =>
                    item.href.includes('notif_t=comment_mention')
                );
        });

    const isTodayNotification = (text) => {
        return true; // Bypass filter tanggal sementara untuk mengizinkan postingan lama
    };

    const todayAggregateLinks = aggregateLinks.filter(item => isTodayNotification(item.text));

    console.log(`Total mention ditemukan di halaman: ${todayAggregateLinks.length}`);

    const results        = [];
    const processedLinks = new Set();  // deduplikasi dalam satu run

    for (const aggregate of todayAggregateLinks) {

        const detailPage = await context.newPage();

        try {

            await detailPage.goto(
                aggregate.href,
                { waitUntil: 'domcontentloaded', timeout: 45000 }
            );

            await detailPage.waitForTimeout(3000);

            const directLinks = await detailPage
                .locator('a')
                .evaluateAll(elements => {
                    return elements
                        .map(el => ({
                            text: el.innerText ? el.innerText.trim() : '',
                            href: el.href || ''
                        }))
                        .filter(item => item.href.includes('comment_id='));
                });

            // Aggregate link sudah lolos filter tanggal — semua directLinks di
            // dalamnya dianggap relevan. Tidak perlu filter tanggal lagi karena
            // teks link di detail page sering kosong sehingga filter akan salah tolak.
            if (directLinks.length === 0) {
                console.log('Tidak ada direct comment_id link di halaman detail.');
                await detailPage.close();
                continue;
            }

            // ── Pre-filter: buang yang sudah ada di DB atau sudah diproses ──
            // Ini penting agar tidak membuka puluhan halaman yang tidak perlu.
            const newDirectLinks = directLinks.filter(item => {
                const match = item.href.match(/comment_id=(\d+)/);
                const cId   = match ? match[1] : null;
                const nLink = normalizeCommentUrl(item.href);

                if (cId && known.ids.has(cId)) return false;
                if (known.links.has(nLink)) return false;
                if (processedLinks.has(nLink)) return false;
                return true;
            });

            // Batasi maksimal 10 link baru per aggregate agar tidak timeout
            const linksToProcess = newDirectLinks.slice(0, 10);

            if (linksToProcess.length === 0) {
                console.log('Semua link di aggregate ini sudah diproses sebelumnya.');
                await detailPage.close();
                continue;
            }

            console.log(`Ditemukan ${linksToProcess.length} link baru dari ${directLinks.length} total di aggregate ini.`);

            for (const direct of linksToProcess) {
                /*
                 * Ekstrak comment_id dari URL
                 */
                const match     = direct.href.match(/comment_id=(\d+)/);
                const commentId = match ? match[1] : null;

                /*
                 * Normalisasi URL untuk stabilitas antar-run
                 */
                const normalizedLink = normalizeCommentUrl(direct.href);

                /*
                 * Skip jika sudah diproses dalam run ini (dari aggregate lain)
                 */
                if (processedLinks.has(normalizedLink)) {
                    console.log(`Skip (duplikat dalam run): ${normalizedLink}`);
                    continue;
                }

                processedLinks.add(normalizedLink);

                /*
                 * Buka halaman komentar untuk ambil isi komentar
                 */
                const commentPage    = await context.newPage();
                let   commentMessage = null;
                let   commenterName  = extractSenderName(direct.text);

                // Validasi sender: tolak jika hanya simbol/titik/kosong
                const isValidSender = (name) => {
                    if (!name) return false;
                    const cleaned = name.replace(/[\s·.…•\-—·]/gu, '');
                    return cleaned.length >= 2;
                };

                try {

                    await commentPage.goto(
                        direct.href,
                        { waitUntil: 'domcontentloaded', timeout: 45000 }
                    );

                    await commentPage.waitForTimeout(5000);

                    await openOriginalText(commentPage);

                    // Jika sender tidak valid, coba ekstrak dari DOM halaman komentar
                    if (!isValidSender(commenterName)) {
                        const domSender = await commentPage.evaluate((cId) => {
                            // Cari anchor yang mengandung comment_id
                            const anchors = Array.from(document.querySelectorAll('a'));
                            const target = anchors.find(a => a.href && a.href.includes('comment_id=') && a.href.includes(cId));
                            if (!target) return null;

                            // Naik ke container komentar
                            let parent = target.parentElement;
                            for (let i = 0; i < 15; i++) {
                                if (!parent) break;
                                const role = parent.getAttribute('role');
                                if (role === 'comment' || role === 'article') break;
                                parent = parent.parentElement;
                            }
                            if (!parent) return null;

                            // Nama biasanya ada di baris pertama teks dalam container
                            const lines = parent.innerText.split('\n').map(l => l.trim()).filter(Boolean);
                            // Filter noise
                            const noise = ['facebook', 'suka', 'balas', 'like', 'reply', 'lihat asli', 'see original'];
                            for (const line of lines) {
                                if (line.length >= 2 && !noise.includes(line.toLowerCase()) && !/^\d/.test(line) && !/^[·.…•\-—]+$/.test(line)) {
                                    return line;
                                }
                            }
                            return null;
                        }, commentId);

                        if (domSender) {
                            commenterName = domSender;
                            console.log(`Sender dikoreksi dari DOM: "${commenterName}"`);
                        }
                    }

                    const isBadExtractedText = (value) => {
                        const text = (value || '').trim().toLowerCase();

                        return !text || [
                            'facebook',
                            'suka',
                            'balas',
                            'like',
                            'reply',
                            'lihat asli',
                            'see original',
                            'view original'
                        ].includes(text) || text.startsWith('lihat asli (');
                    };

                    // 1. Coba cari spesifik berdasarkan commentId di DOM
                    let extractedText = null;
                    if (commentId) {
                        extractedText = await commentPage.evaluate(({ commentId, commenterName }) => {
                            const anchors = Array.from(document.querySelectorAll('a'));
                            const targetAnchor = anchors.find(a => a.href && a.href.includes('comment_id=') && a.href.includes(commentId));
                            if (!targetAnchor) return null;

                            let parent = targetAnchor.parentElement;
                            let container = null;
                            for (let i = 0; i < 15; i++) {
                                if (!parent) break;
                                const role = parent.getAttribute('role');
                                if (role === 'comment' || role === 'article') {
                                    container = parent;
                                    break;
                                }
                                parent = parent.parentElement;
                            }

                            if (!container) {
                                let p = targetAnchor;
                                for (let i = 0; i < 8; i++) {
                                    if (p.parentElement) p = p.parentElement;
                                }
                                container = p;
                            }

                            const lines = container.innerText.split('\n').map(l => l.trim()).filter(Boolean);
                            const idx = lines.findIndex(l => l.toLowerCase() === commenterName.toLowerCase());
                            if (idx !== -1 && lines[idx + 1]) {
                                return lines[idx + 1];
                            }
                            if (lines.length > 1) {
                                return lines[1];
                            }
                            return null;
                        }, { commentId, commenterName });
                    }

                    if (!isBadExtractedText(extractedText)) {
                        commentMessage = extractedText;
                        console.log(`Berhasil mengekstrak komentar dengan targeted search: "${commentMessage}"`);
                    } else {
                        // 2. Fallback: Cari di seluruh body text dari bawah ke atas.
                        // Facebook kadang menaruh banyak anchor "Facebook" dekat comment_id,
                        // jadi targeted search harus diabaikan jika hasilnya noise.
                        const bodyText = await commentPage
                            .locator('body')
                            .innerText();

                        const lines = bodyText
                            .split('\n')
                            .map(line => line.trim())
                            .filter(Boolean);

                        const index = lines.findLastIndex(line => line.toLowerCase() === commenterName.toLowerCase());

                        if (index !== -1 && !isBadExtractedText(lines[index + 1])) {
                            commentMessage = lines[index + 1];
                            console.log(`Berhasil mengekstrak komentar dengan fallback search dari bawah: "${commentMessage}"`);
                        } else {
                            const indexFirst = lines.findIndex(line => line.toLowerCase() === commenterName.toLowerCase());
                            if (indexFirst !== -1 && !isBadExtractedText(lines[indexFirst + 1])) {
                                commentMessage = lines[indexFirst + 1];
                                console.log(`Berhasil mengekstrak komentar dengan fallback search dari atas: "${commentMessage}"`);
                            }
                        }

                        if (!commentMessage) {
                            const simaduCandidateIndex = lines.findIndex(line => {
                                return /^@?simadu\s*kmc\s+.+/i.test(line) &&
                                    !isBadExtractedText(line);
                            });

                            if (simaduCandidateIndex !== -1) {
                                commentMessage = lines[simaduCandidateIndex];

                                if (!commenterName && lines[simaduCandidateIndex - 1]) {
                                    commenterName = lines[simaduCandidateIndex - 1];
                                }

                                console.log(`Berhasil mengekstrak komentar dengan fallback keyword Simadu: "${commentMessage}"`);
                            }
                        }
                    }

                } catch (error) {
                    commentMessage = null;
                }
                
                const cleanMessage = (msg) => {
                    if (!msg) return null;
                    const trimmed = msg.trim();
                    if (!trimmed) return null;

                    const lower = trimmed.toLowerCase();

                    // ── Daftar noise words UI Facebook ──────────────────
                    const facebookNoiseWords = [
                        'dasbor profesional', 'facebook', 'suka', 'balas',
                        'kirim pesan', 'kirim', 'bagikan', 'tulis komentar',
                        'tulis balasan', 'lihat selengkapnya', 'lihat lebih banyak',
                        'sembunyikan', 'lainnya', 'tampilkan balasan',
                        'terjemahkan', 'balasan', 'komentari sebagai',
                        'write a comment', 'write a reply', 'like', 'reply',
                        'share', 'send message', 'see more', 'hide',
                        'translate', 'comment as', 'send', 'view more comments',
                        'lihat komentar lainnya', 'most relevant',
                        'paling relevan', 'semua komentar', 'all comments',
                        'ketapang media center', 'simadu kmc',
                        'lihat asli', 'lihat asli (bahasa sunda)',
                        'see original', 'view original',
                    ];

                    if (facebookNoiseWords.includes(lower)) {
                        return null;
                    }

                    // ── Tolak jika cocok dengan pola noise ──────────────
                    const noisePatterns = [
                        /^\d+\s*(menit|jam|hari|minggu|bulan|tahun|[hjmdbst])\s*(lalu|yang lalu)?$/i,
                        /^\d+\s*(min|hr|[hm]|d|w|mo|yr)s?\s*(ago)?$/i,
                        /^(just now|baru saja)$/i,
                        /^(\d+)\s*$/,                       // angka saja
                        /^[·…•\-—]+$/,                      // hanya simbol
                        /^[\s\p{P}\p{S}]+$/u,               // hanya tanda baca / simbol
                        /^(top fan|penggemar utama|author|penulis)$/i,
                        /^(edited|diedit|disunting)$/i,
                    ];

                    for (const pattern of noisePatterns) {
                        if (pattern.test(trimmed)) {
                            return null;
                        }
                    }

                    // ── Tolak jika hanya emoji ──────────────────────────
                    const withoutEmoji = trimmed.replace(/[\u{1F000}-\u{1FFFF}\u{2600}-\u{27BF}\u{FE00}-\u{FE0F}\u{200D}\u{20E3}\u{E0020}-\u{E007F}\s]/gu, '');
                    if (withoutEmoji.length === 0) {
                        return null;
                    }

                    // ── Tolak jika terlalu pendek (< 8 karakter alfanumerik) ──
                    const alphanumOnly = trimmed.replace(/[^a-zA-Z0-9\u00C0-\u024F]/gu, '');
                    if (alphanumOnly.length < 8) {
                        return null;
                    }

                    // ── Tolak reaksi spam pendek (≤ 5 kata) ─────────────
                    const wordCount = trimmed.split(/\s+/).length;
                    const spamReactions = /\b(amin+|aminn+|aamiin+|wkwk+|haha+|hihi+|hehe+|xd+|lol+|mantap+|mantul+|okeh?|siip+|sip+|jos+|keren+|gass+|semangat|up|bump|nais|nice|gokil+)\b/i;
                    if (spamReactions.test(trimmed) && wordCount <= 5) {
                        return null;
                    }

                    // ── Tolak ucapan / promosi ──────────────────────────
                    const nonComplaint = [
                        'selamat ulang tahun', 'happy birthday', 'hbd', 'met ultah',
                        'happy new year', 'selamat tahun baru', 'merry christmas',
                        'selamat lebaran', 'selamat natal', 'selamat idul fitri',
                        'follback', 'follow back', 'f4f', 'like4like', 'l4l',
                        'giveaway', 'promo', 'diskon', 'jual', 'beli murah',
                        'klik link', 'wa aja', 'hub kami', 'order sekarang',
                    ];
                    for (const kw of nonComplaint) {
                        if (lower.includes(kw)) {
                            return null;
                        }
                    }

                    // ── Bersihkan prefix halaman (Simadu KMC) untuk evaluasi ──
                    const stripped = lower
                        .replace(/^simadu\s*kmc\s*/i, '')
                        .replace(/^@?simadu\s*kmc\s*/i, '')
                        .trim();

                    // Jika setelah dihilangkan prefix tidak ada isi berarti
                    if (!stripped || stripped.length < 5) {
                        return null;
                    }

                    // ── Tolak komentar tes / tanpa konteks ───────────────
                    const testPatterns = [
                        /^tes\s*$/i,                                  // "tes"
                        /^test\s*$/i,                                 // "test"
                        /^tes\s+(komen|komentar|aja|doang|dulu|coba|123|test)/i,  // "tes komen", "tes aja"
                        /^test\s+(comment|only|just|123)/i,           // "test comment"
                        /^coba\s+(komen|komentar|tes|test|aja)/i,     // "coba komen"
                        /^komen\s*\d*[.:]\d*\s*$/i,                   // "komen 11.47", "komen 12:30"
                        /^komentar\s*\d*[.:]\d*\s*$/i,                // "komentar 11.47"
                        /^comment\s*\d*[.:]\d*\s*$/i,                 // "comment 11.47"
                        /^\w+\s+\d{1,2}[.:]\d{2}\s*$/i,              // "kata 12.43" (satu kata + timestamp)
                        /^(halo+|hai+|hi+|hello+|hey+)\s*$/i,        // sapaan saja
                        /^(halo+|hai+|hi+)\s+(min|admin|kak|bang)\s*$/i, // "hai min"
                        /^(min|admin|kak|bang)\s*$/i,                 // panggilan saja
                        /^(tag|cc|mention)\s/i,                       // "tag teman"
                    ];

                    for (const pattern of testPatterns) {
                        if (pattern.test(stripped)) {
                            return null;
                        }
                    }

                    return trimmed;
                };

                commentMessage = cleanMessage(commentMessage);

                // ── Jangan push jika comment_message null (sudah terfilter) ──
                if (commentMessage === null) {
                    console.log(`Komentar dilewati (noise/spam) untuk comment_id=${commentId}`);
                    await commentPage.close();
                    continue;
                }

                // ── Jangan push jika sender tidak valid ──
                if (!isValidSender(commenterName)) {
                    console.log(`Komentar dilewati (sender tidak valid: "${commenterName}") untuk comment_id=${commentId}`);
                    await commentPage.close();
                    continue;
                }

                await commentPage.close();

                results.push({
                    notification_text: direct.text,
                    sender:            commenterName,
                    comment_message:   commentMessage,
                    comment_link:      normalizedLink,
                    comment_id:        commentId
                });

                console.log(`Mention baru ditemukan: comment_id=${commentId}`);
            }

        } catch (error) {
            // Abaikan jika gagal membuka satu notifikasi
            console.log('Error saat proses notifikasi: ' + error.message);
        }

        await detailPage.close();
    }

    console.log(`Selesai. ${results.length} mention baru akan dikirim ke Laravel.`);

    /*
     * Output JSON untuk Laravel
     */
    console.log(JSON.stringify(results, null, 4));

    await browser.close();

})();