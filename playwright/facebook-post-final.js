const { chromium } = require('playwright');
const crypto = require('crypto');

(async () => {

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
        
        // Auto-klik "Lihat Asli" untuk disable translate Facebook
        const observer = new MutationObserver(() => {
            document.querySelectorAll('span, div').forEach(el => {
                const text = el.textContent?.trim();
                if (text?.includes('Lihat Asli') || text?.includes('See Original')) {
                    const button = el.closest('div[role="button"]');
                    if (button && !button.dataset.clicked) {
                        button.dataset.clicked = 'true';
                        button.click();
                    }
                }
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
    });

    const page = await context.newPage();

    const results = [];
    const seenKeys = new Set();

    const normalize = (value) =>
        (value || '')
            .replace(/\s+/g, ' ')
            .trim();

    const makeFingerprint = (sender, message) => {
        const raw = `${normalize(sender).toLowerCase()}|${normalize(message).toLowerCase()}`;

        return crypto
            .createHash('md5')
            .update(raw)
            .digest('hex');
    };

    const makeStableLink = (baseUrl, fingerprint) => {
        return `${baseUrl.replace(/#$/, '')}#post-${fingerprint.slice(0, 12)}`;
    };

    const isNoiseLine = (line) => {
        const text = normalize(line);
        const lower = text.toLowerCase();

        if (!text) {
            return true;
        }

        // ── Noise word tunggal / UI Facebook ────────────────────
        const noiseWords = [
            'facebook', '·', '…', '•', 'dasbor profesional',
            'suka', 'balas', 'kirim pesan', 'kirim', 'bagikan',
            'tulis komentar', 'tulis balasan', 'lihat selengkapnya',
            'lihat lebih banyak', 'sembunyikan', 'lainnya',
            'tampilkan balasan', 'terjemahkan', 'balasan',
            'komentari sebagai', 'write a comment', 'write a reply',
            'like', 'reply', 'share', 'send message', 'see more',
            'hide', 'translate', 'comment as', 'send',
            'view more comments', 'lihat komentar lainnya',
            'most relevant', 'paling relevan', 'semua komentar',
            'all comments', 'suggested for you', 'disarankan untuk anda',
            'orang lain juga menyukai', 'people also liked',
            'ketapang media center', 'simadu kmc',
            'top fan', 'penggemar utama', 'author', 'penulis',
            'edited', 'diedit', 'disunting', 'public', 'publik',
            'pinned', 'disematkan',
        ];

        if (noiseWords.includes(lower)) {
            return true;
        }

        // ── Pola noise regex ────────────────────────────────────
        const noisePatterns = [
            /komentari sebagai/i,
            /^\d+\s*(menit|jam|hari|minggu|bulan|tahun|[hjmdbst])\s*(lalu|yang lalu)?$/i,
            /^\d+\s*(min|hr|[hm]|d|w|mo|yr)s?\s*(ago)?$/i,
            /^(just now|baru saja)$/i,
            /^\d+\s*(suka|like|komentar|comment|bagikan|share)s?$/i,
            /^(\d+)\s*$/,
            /^[\p{P}\p{S}]+$/u,
            /^.{0,1}$/,
        ];

        for (const pattern of noisePatterns) {
            if (pattern.test(text)) {
                return true;
            }
        }

        return false;
    };

    const isSenderCandidate = (line) => {
        const text = normalize(line);
        const lower = text.toLowerCase();

        if (isNoiseLine(text)) {
            return false;
        }

        if (lower.includes('simadu kmc')) {
            return false;
        }

        if (/^\d+$/.test(text)) {
            return false;
        }

        if (/^[\p{P}\p{S}]+$/u.test(text)) {
            return false;
        }

        if (!/[A-Za-zÀ-ÿ]/.test(text)) {
            return false;
        }

        if (text.length < 2) {
            return false;
        }

        return true;
    };

    const findSender = (lines, index) => {
        for (
            let j = index - 1;
            j >= Math.max(0, index - 120);
            j--
        ) {
            const candidate = normalize(lines[j]);

            if (isSenderCandidate(candidate)) {
                return candidate;
            }
        }

        return null;
    };

    /**
     * Validasi apakah pesan postingan layak diproses sebagai aduan.
     * Menolak: terlalu pendek, hanya emoji, hanya tag mention tanpa isi, spam, dsb.
     */
    const isValidPostMessage = (msg) => {
        if (!msg) return false;

        const text = normalize(msg);
        const lower = text.toLowerCase();

        // Tolak jika noise
        if (isNoiseLine(text)) return false;

        // Tolak jika hanya emoji
        const withoutEmoji = text.replace(/[\u{1F000}-\u{1FFFF}\u{2600}-\u{27BF}\u{FE00}-\u{FE0F}\u{200D}\u{20E3}\u{E0020}-\u{E007F}\s]/gu, '');
        if (withoutEmoji.length === 0) return false;

        // Tolak jika terlalu pendek (< 8 karakter alfanumerik)
        const alphanumOnly = text.replace(/[^a-zA-Z0-9\u00C0-\u024F]/gu, '');
        if (alphanumOnly.length < 8) return false;

        // Tolak reaksi spam pendek (≤ 5 kata)
        const wordCount = text.split(/\s+/).length;
        const spamReactions = /\b(amin+|aminn+|aamiin+|wkwk+|haha+|hihi+|hehe+|xd+|lol+|mantap+|mantul+|okeh?|siip+|sip+|jos+|keren+|gass+|semangat|up|bump|nais|nice|gokil+)\b/i;
        if (spamReactions.test(text) && wordCount <= 5) return false;

        // Tolak ucapan / promosi
        const nonComplaint = [
            'selamat ulang tahun', 'happy birthday', 'hbd', 'met ultah',
            'happy new year', 'selamat tahun baru', 'merry christmas',
            'selamat lebaran', 'selamat natal', 'selamat idul fitri',
            'follback', 'follow back', 'f4f', 'like4like', 'l4l',
            'giveaway', 'promo', 'diskon', 'jual', 'beli murah',
            'klik link', 'wa aja', 'hub kami', 'order sekarang',
        ];
        for (const kw of nonComplaint) {
            if (lower.includes(kw)) return false;
        }

        return true;
    };

    try {

        console.log(
            'Membuka notifikasi Facebook...'
        );

        await page.goto(
            'https://web.facebook.com/notifications',
            {
                waitUntil: 'networkidle'
            }
        );

        await page.waitForTimeout(5000);

        /*
         * Ambil semua notifikasi mention postingan
         */
        const notifications = await page.$$eval(
            'a[href]',
            elements =>
                elements
                    .map(el => ({
                        text: el.innerText
                            ? el.innerText.trim()
                            : '',
                        href: el.href || ''
                    }))
                    .filter(item =>
                        item.href.includes(
                            'notif_t=mention'
                        )
                    )
        );

        const isTodayNotification = (text) => {
            return true; // Bypass filter tanggal sementara untuk mengizinkan postingan lama
        };

        const todayNotifications = notifications.filter(n => isTodayNotification(n.text));

        console.log(
            'Jumlah mention:',
            todayNotifications.length
        );

        if (todayNotifications.length === 0) {
            console.log(
                JSON.stringify(
                    [],
                    null,
                    4
                )
            );

            await browser.close();
            return;
        }

        /*
         * Ambil mention terbaru
         */
        const latestMention = todayNotifications[0];

        console.log(
            'Membuka halaman mention...'
        );

        await page.goto(
            latestMention.href,
            {
                waitUntil: 'networkidle'
            }
        );

        await page.waitForTimeout(5000);

        console.log('URL saat ini:', page.url());

        console.log('Mencari tombol "Lihat Selengkapnya"...');
        try {
            const seeMoreTexts = ['Lihat Selengkapnya', 'See more', 'See More'];
            for (const text of seeMoreTexts) {
                const locators = page.locator(`div[role="button"]:has-text("${text}")`);
                const count = await locators.count();
                for (let i = 0; i < count; i++) {
                    await locators.nth(i).click({ timeout: 1000 }).catch(() => {});
                }
            }
            await page.waitForTimeout(2000);
        } catch (e) {}

        /*
         * Jika langsung ke permalink, gunakan cara lama
         */
        if (
            page.url().includes('permalink.php') || page.url().includes('/posts/')
        ) {
            const title = await page.title();
            let titleText = title.replace(' | Facebook', '').trim();

            // Coba extract sender dari title dengan regex
            let sender = null;
            const senderMatch = titleText.match(/^(.+?)\s-\s(.+)$/);
            if (senderMatch) {
                sender = senderMatch[1];
            }

            // Fallback: extract sender dari DOM (author name)
            if (!sender || sender.toLowerCase().includes('simadu')) {
                try {
                    sender = await page.$eval(
                        'h2 a[role="link"], h3 a[role="link"], strong a[role="link"]',
                        el => el.textContent.trim()
                    ).catch(() => null);
                } catch (e) {}
            }

            // Jika masih gagal, coba dari meta tag author
            if (!sender || sender.toLowerCase().includes('simadu')) {
                try {
                    sender = await page.$eval(
                        'meta[property="og:title"], meta[name="author"]',
                        el => el.getAttribute('content')
                    ).catch(() => null);
                    if (sender) {
                        // Clean og:title yang biasanya format "Nama | Facebook"
                        sender = sender.replace(' | Facebook', '').split(' - ')[0].trim();
                    }
                } catch (e) {}
            }

            let postMessage = '';
            try {
                const texts = await page.$$eval('div[dir="auto"]', els => els.map(e => e.innerText));
                for (const t of texts) {
                    if (t && t.toLowerCase().includes('simadu') && t.length > postMessage.length) {
                        postMessage = t;
                    }
                }
            } catch (e) {}

            if (!postMessage) {
                postMessage = senderMatch ? senderMatch[2] : titleText;
            }

            postMessage = normalize(postMessage);

            if (
                isValidPostMessage(postMessage)
            ) {
                const fingerprint = makeFingerprint(
                    sender || '',
                    postMessage
                );

                if (!seenKeys.has(fingerprint)) {
                    seenKeys.add(fingerprint);

                    results.push({
                        sender,
                        notification_text: sender
                            ? `${sender} menyebut Anda dalam postingan.`
                            : 'Seseorang menyebut Anda dalam postingan.',
                        post_message: postMessage,
                        post_link: makeStableLink(
                            page.url(),
                            fingerprint
                        )
                    });
                }
            }
        }

        /*
         * Jika halaman agregasi mentions
         */
        else if (
            page.url().includes(
                '/mentions/'
            )
        ) {
            const body = await page.locator('body').innerText();

            const lines = body
                .split('\n')
                .map(line => normalize(line))
                .filter(Boolean);

            /*
             * Baca dari bawah ke atas:
             * postingan terlama dulu -> terbaru
             */
            for (
                let i = lines.length - 1;
                i >= 0;
                i--
            ) {
                const currentLine = normalize(lines[i]);
                const lowerLine = currentLine.toLowerCase();

                /*
                 * Cari baris postingan yang benar
                 */
                if (
                    lowerLine.includes('simadu kmc') &&
                    !lowerLine.includes('komentari sebagai')
                ) {
                    const postMessage = currentLine;

                    // ── Validasi konten postingan ────────────────
                    if (!isValidPostMessage(postMessage)) {
                        console.log('Postingan dilewati (noise/spam): ' + postMessage.substring(0, 80));
                        continue;
                    }

                    const sender = findSender(lines, i);

                    const fingerprint = makeFingerprint(
                        sender || '',
                        postMessage
                    );

                    if (seenKeys.has(fingerprint)) {
                        continue;
                    }

                    seenKeys.add(fingerprint);

                    results.push({
                        sender,
                        notification_text: sender
                            ? `${sender} menyebut Anda dalam postingan.`
                            : 'Seseorang menyebut Anda dalam postingan.',
                        post_message: postMessage,
                        post_link: makeStableLink(
                            page.url(),
                            fingerprint
                        )
                    });
                }
            }
        }

        console.log(
            JSON.stringify(
                results,
                null,
                4
            )
        );

    } catch (error) {
        console.error(error.message);
    }

    await browser.close();

})();