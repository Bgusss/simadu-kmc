const { chromium } = require('playwright');
const crypto = require('crypto');
const fs = require('fs');
const path = require('path');

const SESSION_FILE = path.resolve(__dirname, 'instagram-session.json');

(async () => {
    const sessionExists = fs.existsSync(SESSION_FILE);

    const browser = await chromium.launch({
        headless: false,
        args: [
            '--disable-blink-features=AutomationControlled',
            '--disable-features=Translate',
            '--disable-translate',
            '--disable-extensions',
            '--lang=id-ID'
        ]
    });

    const contextOptions = {
        locale: 'id-ID',
        timezoneId: 'Asia/Pontianak',
        viewport: { width: 1920, height: 1080 },
        extraHTTPHeaders: {
            'Accept-Language': 'id-ID,id;q=0.9'
        }
    };

    if (sessionExists) {
        contextOptions.storageState = SESSION_FILE;
    }

    const context = await browser.newContext(contextOptions);

    await context.addInitScript(() => {
        Object.defineProperty(navigator, 'languages', { get: () => ['id-ID', 'id'] });
        Object.defineProperty(navigator, 'language', { get: () => 'id-ID' });
    });

    const page = await context.newPage();
    const results = [];
    const seenKeys = new Set();

    const normalize = (value) => (value || '').replace(/\s+/g, ' ').trim();

    const makeFingerprint = (sender, message) => {
        const raw = `${normalize(sender).toLowerCase()}|${normalize(message).toLowerCase()}`;
        return crypto.createHash('md5').update(raw).digest('hex');
    };

    const isNoiseLine = (line) => {
        const text = normalize(line);
        if (!text) return true;
        const noisePatterns = [
            /^\d+\s*(menit|jam|hari|minggu|bulan|tahun|[hjmdbst])\s*(lalu|yang lalu)?$/i,
            /^\d+\s*(min|hr|[hm]|d|w|mo|yr)s?\s*(ago)?$/i,
            /^(just now|baru saja|sekarang)$/i,
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

        const testPatterns = [
            /^tes\s*$/i, /^test\s*$/i, /^(halo+|hai+|hi+|hello+|hey+)\s*$/i,
            /^(halo+|hai+|hi+)\s+(min|admin|kak|bang)\s*$/i,
            /^(min|admin|kak|bang)\s*$/i,
            /^p\s*$/i, /^ping\s*$/i
        ];
        for (const pattern of testPatterns) {
            if (pattern.test(lower)) return false;
        }

        const nonComplaint = [
            'selamat ulang tahun', 'happy birthday', 'hbd', 'met ultah',
            'selamat tahun baru', 'merry christmas', 'follback', 'promo', 'diskon'
        ];
        for (const kw of nonComplaint) {
            if (lower.includes(kw)) return false;
        }

        return true;
    };

    const saveSession = async () => {
        try {
            const state = await context.storageState();
            fs.writeFileSync(SESSION_FILE, JSON.stringify(state, null, 2));
            console.log('💾 Session Instagram tersimpan di:', SESSION_FILE);
        } catch (e) {
            console.error('Gagal menyimpan session:', e.message);
        }
    };

    const dismissPopups = async () => {
        const dismissTexts = ['Not Now', 'Lain Kali', 'Nanti Saja', 'Lewati'];
        for (const text of dismissTexts) {
            try {
                const btn = page.locator(`button:has-text("${text}"), div[role="button"]:has-text("${text}")`);
                if (await btn.count() > 0) {
                    await btn.first().click({ force: true, timeout: 2000 });
                    await page.waitForTimeout(500);
                }
            } catch (e) {}
        }
    };

    try {
        // ═══════════════════════════════════════════════
        // FASE 0: CEK SESSION
        // ═══════════════════════════════════════════════
        if (!sessionExists) {
            console.log('❌ Session Instagram tidak ditemukan.');
            console.log('   Jalankan dulu: node login-instagram.js');
            console.log('   Login manual, verifikasi email, lalu tekan Enter.');
            await browser.close();
            process.exit(1);
        }

        // ═══════════════════════════════════════════════
        // FASE 1: AUTO-ACCEPT MESSAGE REQUESTS
        // ═══════════════════════════════════════════════
        console.log('--- FASE 1: Memeriksa Permintaan Pesan (Requests) ---');
        await page.goto('https://www.instagram.com/direct/requests/', {
            waitUntil: 'domcontentloaded',
            timeout: 30000
        });
        await page.waitForTimeout(3000);

        // Cek apakah session masih valid
        if (page.url().includes('/accounts/login')) {
            console.log('⚠️  Session expired! Menghapus session lama...');
            if (fs.existsSync(SESSION_FILE)) fs.unlinkSync(SESSION_FILE);
            console.log('   Jalankan ulang script untuk login kembali.');
            await browser.close();
            process.exit(1);
        }

        await dismissPopups();

        const reqLocators = page.locator('a[href^="/direct/t/"], div[role="listitem"]')
            .filter({ hasText: /·|\d+\s*(m|h|d|w|min|jam|hari)/i })
            .filter({ hasNotText: /Hidden Requests|Permintaan Tersembunyi/i });

        let reqCount = await reqLocators.count();
        if (reqCount > 0) {
            const maxReq = Math.min(reqCount, 5);
            console.log(`Ditemukan ${reqCount} permintaan pesan. Memproses maks ${maxReq}...`);

            for (let i = 0; i < maxReq; i++) {
                try {
                    const freshReqLocators = page.locator('a[href^="/direct/t/"], div[role="listitem"]')
                        .filter({ hasText: /·|\d+\s*(m|h|d|w|min|jam|hari)/i })
                        .filter({ hasNotText: /Hidden Requests|Permintaan Tersembunyi/i });

                    if (await freshReqLocators.count() === 0) break;

                    await freshReqLocators.first().click({ force: true });
                    await page.waitForTimeout(2000);

                    const acceptBtn = page.locator('button, div[role="button"]')
                        .filter({ hasText: /^Accept$|^Terima$/i });

                    if (await acceptBtn.count() > 0) {
                        console.log(`  ✅ Menerima permintaan ke-${i + 1}...`);
                        await acceptBtn.first().click({ force: true });
                        await page.waitForTimeout(1500);

                        const moveGeneralBtn = page.locator('button, div[role="button"]')
                            .filter({ hasText: /^General$|^Umum$/i })
                            .filter({ hasNotText: 'Settings' });

                        if (await moveGeneralBtn.count() > 0) {
                            console.log(`  📂 Memindahkan ke tab General...`);
                            await moveGeneralBtn.first().click({ force: true });
                            await page.waitForTimeout(1000);
                        }
                    } else {
                        console.log(`  ⏭️ Permintaan ke-${i + 1}: tidak ada tombol Accept.`);
                    }

                    await page.goto('https://www.instagram.com/direct/requests/', {
                        waitUntil: 'domcontentloaded',
                        timeout: 15000
                    });
                    await page.waitForTimeout(1500);
                } catch (e) {
                    console.log(`  ❌ Gagal memproses permintaan ke-${i + 1}: ${e.message}`);
                }
            }
        } else {
            console.log('Tidak ada pesan baru di tab Requests.');
        }

        // ═══════════════════════════════════════════════
        // FASE 2: EKSTRAK ADUAN DARI GENERAL/INBOX
        // ═══════════════════════════════════════════════
        console.log('\n--- FASE 2: Mengekstrak Aduan dari DM ---');
        await page.goto('https://www.instagram.com/direct/inbox/', {
            waitUntil: 'domcontentloaded',
            timeout: 30000
        });
        await page.waitForTimeout(3000);
        await dismissPopups();

        const generalTab = page.locator('div[role="tab"], button, span')
            .filter({ hasText: /^General$|^Umum$/i })
            .filter({ hasNotText: /Settings|Pengaturan/i });

        if (await generalTab.count() > 0) {
            await generalTab.first().click({ force: true });
            await page.waitForTimeout(3000);
            console.log('Tab General/Umum ditemukan dan diklik.');
        } else {
            console.log('Tab General tidak ditemukan — menggunakan inbox utama.');
        }

        try {
            await page.waitForSelector('div[role="button"]', { timeout: 10000 });
        } catch(e) {}

        let chatLocators = page.locator('div[role="button"]').filter({ hasText: /·/ });
        let chatCount = await chatLocators.count();

        if (chatCount === 0) {
            console.log('Tidak ada obrolan ditemukan (Pastikan elemen UI sesuai).');
        } else {
            const maxProcess = Math.min(chatCount, 8);
            console.log(`Ditemukan ${chatCount} obrolan. Memproses ${maxProcess} teratas...`);

            for (let i = 0; i < maxProcess; i++) {
                try {
                    let freshChats = page.locator('div[role="button"]').filter({ hasText: /·/ });
                    if (i >= await freshChats.count()) break;

                    const chatItem = freshChats.nth(i);
                    const itemText = await chatItem.innerText().catch(() => '');
                    const lines = itemText.split('\n')
                        .map(l => l.trim())
                        .filter(l => l && !isNoiseLine(l));

                    let senderName = 'Pengirim DM';
                    if (lines.length > 0) {
                        const candidate = lines[0];
                        if (candidate.length < 40 && !candidate.toLowerCase().includes('min ')) {
                            senderName = candidate;
                        }
                    }

                    await chatItem.click({ force: true });
                    
                    let chatOpened = false;
                    for (let attempt = 0; attempt < 10; attempt++) {
                        const headerText = await page.evaluate(() => {
                            const headerEl = document.querySelector('div[role="main"] header');
                            return headerEl ? (headerEl.innerText || '') : '';
                        });
                        if (headerText.toLowerCase().includes(senderName.toLowerCase())) {
                            chatOpened = true;
                            break;
                        }
                        await page.waitForTimeout(500);
                    }

                    if (!chatOpened) {
                        await page.waitForTimeout(2000);
                    } else {
                        await page.waitForTimeout(1500);
                    }
 
                    const targetMessage = await page.evaluate((senderName) => {
                        const chatArea = document.querySelector('main[role="main"] > section > div > div > div:not([role="navigation"])');
                        if (!chatArea) return null;

                        const spans = Array.from(chatArea.querySelectorAll('span'));
                        if (spans.length === 0) return null;

                        const ignoreTexts = [
                            'kirim pesan...', 'lihat profil', 'aktif', 'active', 'instagram', 'search', 'cari',
                            'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu', 'kemarin', 'hari ini'
                        ];

                        for (let k = spans.length - 1; k >= 0; k--) {
                            const span = spans[k];
                            const text = span.innerText ? span.innerText.trim() : '';
                            const lowerText = text.toLowerCase();

                            if (!text || text.length < 8) continue;
                            if (text.includes('\n')) continue;
                            if (ignoreTexts.some(ignore => lowerText.includes(ignore))) continue;

                            const isDateTime = /^\d{1,2}\s+[A-Za-z]{3,9}\s+\d{4}/.test(text) || 
                                               /^\d{1,2}\.\d{2}$/.test(text) || 
                                               /^\d{1,2}:\d{2}$/.test(text) ||
                                               /^[a-zA-Z]+\s+\d{1,2}\.\d{2}$/.test(text);
                            if (isDateTime) continue;

                            let isInbound = false;
                            let current = span;
                            for (let j = 0; j < 5; j++) {
                                if (!current) break;
                                if (current.getAttribute('role') === 'presentation') {
                                    const style = window.getComputedStyle(current);
                                    if (style.backgroundColor && style.backgroundColor !== 'rgba(0, 0, 0, 0)' && style.backgroundColor !== 'transparent') {
                                        isInbound = true;
                                        break;
                                    }
                                }
                                current = current.parentElement;
                            }

                            if (!isInbound) continue;

                            return text;
                        }

                        return null;
                    }, senderName);

                    const recentMessages = targetMessage ? [targetMessage] : [];
                    let foundValid = false;
 
                    for (const msg of recentMessages) {
                        const isValid = isValidPostMessage(msg);
                        console.log(`  🔍 Mengevaluasi pesan dari ${senderName}: "${normalize(msg).substring(0, 60)}..." | Valid? ${isValid}`);
                        
                        if (isValid) {
                            const fingerprint = makeFingerprint(senderName, msg);
                            if (!seenKeys.has(fingerprint)) {
                                seenKeys.add(fingerprint);
 
                                const threadUrl = page.url().split('?')[0];
                                const uniqueLink = `${threadUrl}#msg-${fingerprint.substring(0, 12)}`;
 
                                results.push({
                                    notification_text: `Pesan DM dari ${senderName}`,
                                    sender: senderName,
                                    message_type: 'dm',
                                    post_message: normalize(msg),
                                    post_link: uniqueLink
                                });
                                foundValid = true;
                                console.log(`  📩 ${senderName}: "${normalize(msg).substring(0, 60)}..."`);
                            }
                        }
                    }

                    if (!foundValid) {
                        console.log(`  ⏭️ ${senderName}: tidak ada aduan valid (spam/sapaan/sudah diproses).`);
                    }

                    await page.goto('https://www.instagram.com/direct/inbox/', {
                        waitUntil: 'domcontentloaded',
                        timeout: 15000
                    });
                    await page.waitForTimeout(1500);

                    const genTab = page.locator('div[role="tab"], button, span')
                        .filter({ hasText: /^General$|^Umum$/i })
                        .filter({ hasNotText: /Settings|Pengaturan/i });
                    if (await genTab.count() > 0) {
                        await genTab.first().click({ force: true });
                        await page.waitForTimeout(1000);
                    }
                } catch (e) {
                    console.log(`  ❌ Gagal memproses obrolan ke-${i + 1}: ${e.message}`);
                }
            }
        }

        await saveSession();

        console.log('\n===== HASIL EKSTRAKSI DM =====\n');
        console.log(JSON.stringify(results, null, 4));

    } catch (error) {
        console.error("Fatal Error:", error.message);
    }

    await browser.close();
})();
