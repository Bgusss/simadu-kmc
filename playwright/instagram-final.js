const { chromium } = require('playwright');
const crypto = require('crypto');

(async () => {

    const browser = await chromium.launch({
        headless: false
    });

    const context = await browser.newContext({
        storageState: 'instagram-session.json',
        locale: 'id-ID',  // Bahasa Indonesia original
        timezoneId: 'Asia/Pontianak'
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

    const isNoiseLine = (line) => {
        const text = normalize(line);
        const lower = text.toLowerCase();

        if (!text) return true;

        const noiseWords = [
            'suka', 'balas', 'kirim pesan', 'kirim', 'bagikan',
            'tulis komentar', 'lihat selengkapnya',
            'sembunyikan', 'lainnya', 'terjemahkan', 'balasan',
            'like', 'reply', 'share', 'send message', 'see more',
            'hide', 'translate', 'send', 'view more comments',
            'lihat komentar lainnya', 'paling relevan',
            'simadu kmc', 'simadu_kmc', 'diedit', 'disunting', 'edited'
        ];

        if (noiseWords.includes(lower)) return true;

        const noisePatterns = [
            /^\d+\s*(menit|jam|hari|minggu|bulan|tahun|[hjmdbst])\s*(lalu|yang lalu)?$/i,
            /^\d+\s*(min|hr|[hm]|d|w|mo|yr)s?\s*(ago)?$/i,
            /^(just now|baru saja|sekarang)$/i,
            /^\d+\s*(suka|like|komentar|comment|bagikan|share)s?$/i,
            /^(\d+)\s*$/,
            /^[\p{P}\p{S}]+$/u,
            /^.{0,1}$/,
        ];

        for (const pattern of noisePatterns) {
            if (pattern.test(text)) return true;
        }

        return false;
    };

    const isValidPostMessage = (msg) => {
        if (!msg) return false;

        const text = normalize(msg);
        const lower = text.toLowerCase();

        if (isNoiseLine(text)) return false;

        const withoutEmoji = text.replace(/[\u{1F000}-\u{1FFFF}\u{2600}-\u{27BF}\u{FE00}-\u{FE0F}\u{200D}\u{20E3}\u{E0020}-\u{E007F}\s]/gu, '');
        if (withoutEmoji.length === 0) return false;

        const alphanumOnly = text.replace(/[^a-zA-Z0-9\u00C0-\u024F]/gu, '');
        if (alphanumOnly.length < 8) return false;

        const wordCount = text.split(/\s+/).length;
        const spamReactions = /\b(amin+|aminn+|aamiin+|wkwk+|haha+|hihi+|hehe+|xd+|lol+|mantap+|mantul+|okeh?|siip+|sip+|jos+|keren+|gass+|semangat|up|bump|nais|nice|gokil+)\b/i;
        if (spamReactions.test(text) && wordCount <= 5) return false;

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
        console.log('Membuka beranda Instagram untuk mencari username...');
        await page.goto('https://www.instagram.com/', { waitUntil: 'networkidle' });
        await page.waitForTimeout(3000);

        // Cari username kita sendiri dari tombol navigasi profil
        // Tombol profil biasanya berupa link dengan gambar profile picture
        const profileHref = await page.locator('a[role="link"]:has(img[alt*="profile picture"])').first().getAttribute('href').catch(() => null);
        let myUsername = 'smd.kmc'; // Default fallback
        if (profileHref) {
            myUsername = profileHref.replace(/\//g, '');
            console.log('Username terdeteksi:', myUsername);
        }

        console.log('Membuka halaman /notifications...');
        await page.goto('https://www.instagram.com/notifications', { waitUntil: 'networkidle' });
        await page.waitForTimeout(4000);

        const locators = page.locator('text=/menyebut|menandai|mentioned|tagged/i');
        const count = await locators.count();

        const notifications = [];
        const seenNotifs = new Set();

        for (let i = 0; i < count; i++) {
            const el = locators.nth(i);
            let text = await el.innerText();
            text = normalize(text.replace(/\n/g, ' '));

            let parent = el;
            for (let j = 0; j < 5; j++) {
                parent = parent.locator('xpath=..');
            }

            const links = await parent.locator('a[href]').evaluateAll(elements => elements.map(e => e.href));
            
            const postLink = links.find(href => href.includes('/p/') || href.includes('/reel/'));
            let targetHref = postLink || (links.length > 0 ? links[0] : null);
            
            if (targetHref) {
                const key = targetHref + '|' + text;
                if (!seenNotifs.has(key)) {
                    seenNotifs.add(key);
                    
                    const sender = text.split(' ')[0];
                    let type = 'post';
                    if (text.toLowerCase().includes('comment') || text.toLowerCase().includes('komentar')) {
                        type = 'comment';
                    }

                    notifications.push({ text, href: targetHref, sender, type, hasDirectPostLink: !!postLink });
                }
            }
        }

        // Cek apakah ada notifikasi postingan yang tidak punya link langsung
        const needsTaggedTab = notifications.some(n => n.type === 'post' && !n.hasDirectPostLink);
        
        let taggedPosts = [];
        if (needsTaggedTab) {
            console.log(`Membuka tab Tagged (/${myUsername}/tagged/) untuk mencari link postingan...`);
            await page.goto(`https://www.instagram.com/${myUsername}/tagged/`, { waitUntil: 'networkidle' });
            await page.waitForTimeout(4000);
            
            taggedPosts = await page.locator('a[href*="/p/"], a[href*="/reel/"]').evaluateAll(els => els.map(e => e.href));
            console.log('Ditemukan link di tab Tagged:', taggedPosts.length);
        }

        // Buka setiap link untuk mengambil kontennya
        for (const notif of notifications) {
            
            // Jika ini tag postingan tapi kita hanya punya link profil, coba cari link aslinya di tab Tagged
            if (notif.type === 'post' && !notif.hasDirectPostLink) {
                const matchedPost = taggedPosts.find(link => link.includes(`/${notif.sender}/p/`) || link.includes(`/${notif.sender}/reel/`));
                if (matchedPost) {
                    console.log(`Berhasil mencocokkan post dari tab Tagged untuk ${notif.sender}`);
                    notif.href = matchedPost;
                } else if (taggedPosts.length > 0) {
                    // Fallback: gunakan post terbaru dari tagged tab (jika asumsinya itu notif terbaru)
                    notif.href = taggedPosts[0];
                } else {
                    console.log(`Tidak dapat menemukan link post untuk notifikasi: ${notif.text}`);
                    continue; // Lewati jika benar-benar tidak ada link post
                }
            } else if (notif.type === 'comment' && !notif.hasDirectPostLink) {
                // Sangat jarang terjadi, biasanya comment punya link post
                continue;
            }

            const detailPage = await context.newPage();
            try {
                await detailPage.goto(notif.href, { waitUntil: 'networkidle' });
                await detailPage.waitForTimeout(4000);

                let extractedMessage = null;

                // Coba ambil teks dari body
                const bodyText = await detailPage.locator('main').innerText().catch(() => '');
                const lines = bodyText.split('\n').map(l => normalize(l)).filter(Boolean);

                if (notif.type === 'post') {
                    // Untuk postingan, biasanya caption adalah blok teks pertama setelah nama pengirim
                    const senderIndex = lines.findIndex(l => l.toLowerCase() === notif.sender.toLowerCase());
                    if (senderIndex !== -1) {
                        // Kumpulkan beberapa baris setelah nama pengirim sampai ketemu noise word (seperti "1h", "Reply", dll)
                        let caption = [];
                        for (let k = senderIndex + 1; k < lines.length; k++) {
                            if (isNoiseLine(lines[k])) break;
                            caption.push(lines[k]);
                        }
                        extractedMessage = caption.join('\n');
                    }
                    
                    // Fallback jika tidak ketemu, ambil <h1> atau teks pertama yang panjang
                    if (!extractedMessage || extractedMessage.length < 10) {
                        const h1 = await detailPage.locator('h1').first().innerText().catch(() => '');
                        if (h1 && !isNoiseLine(h1)) {
                            extractedMessage = h1;
                        } else {
                            extractedMessage = lines.find(l => l.length > 20 && !isNoiseLine(l));
                        }
                    }
                } else {
                    // Untuk komentar, cari baris pengirim di bawah, atau cari username kita
                    const senderIndices = [];
                    lines.forEach((l, idx) => {
                        if (l.toLowerCase() === notif.sender.toLowerCase()) senderIndices.push(idx);
                    });
                    
                    // Biasanya komentar ada di bawah username, kita coba cek satu per satu
                    for (const idx of senderIndices) {
                        let commentText = lines[idx + 1];
                        if (commentText && !isNoiseLine(commentText)) {
                            extractedMessage = commentText;
                            break;
                        }
                    }
                    
                    // Fallback: ambil baris yang mengandung "@" atau panjang
                    if (!extractedMessage) {
                        extractedMessage = lines.find(l => l.includes('@') && !isNoiseLine(l));
                    }
                }

                if (isValidPostMessage(extractedMessage)) {
                    const fingerprint = makeFingerprint(notif.sender, extractedMessage);
                    
                    if (!seenKeys.has(fingerprint)) {
                        seenKeys.add(fingerprint);
                        
                        results.push({
                            notification_text: notif.text,
                            sender: notif.sender,
                            message_type: notif.type,
                            post_message: normalize(extractedMessage),
                            post_link: notif.href,
                        });
                    }
                }
            } catch (err) {
                // Abaikan error individual
            }
            await detailPage.close();
        }

        // Output akhir berupa JSON Array (untuk dibaca Laravel)
        console.log(JSON.stringify(results, null, 4));

    } catch (error) {
        console.error("Fatal Error:", error.message);
    }

    await browser.close();
})();
