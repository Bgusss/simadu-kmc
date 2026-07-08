const { chromium } = require('playwright');
const crypto = require('crypto');

(async () => {
    const browser = await chromium.launch({ headless: true });
    const context = await browser.newContext({ storageState: 'instagram-session.json' });
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
        const lower = text.toLowerCase();
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

    try {
        console.log('Membuka halaman Inbox DM...');
        await page.goto('https://www.instagram.com/direct/inbox/', { waitUntil: 'networkidle' });
        await page.waitForTimeout(2000);

        // Tutup popup "Not Now" / "Lain Kali" jika ada
        await page.getByRole('button', { name: 'Not Now' }).click({ force: true }).catch(() => {});
        await page.getByRole('button', { name: 'Lain Kali' }).click({ force: true }).catch(() => {});
        await page.waitForTimeout(1000);

        // Fungsi pembantu untuk memproses list obrolan saat ini
        const processChatList = async (tabName) => {
            console.log(`\n=== Memproses tab: ${tabName} ===`);
            // Tunggu sebentar agar list termuat setelah pindah tab
            await page.waitForTimeout(1000); 

            // Ambil daftar chat di tab yang sedang aktif
            // Chat Instagram menggunakan div[role="button"] atau a[href...]. 
            // Ciri khas baris obrolan adalah memiliki titik tengah "·" untuk waktu (misal "· 1m")
            const chatLocators = page.locator('a[href^="/direct/t/"], div[role="button"], div[role="listitem"]')
                .filter({ hasText: '·' })
                .filter({ hasNotText: /Hidden Requests|Permintaan Tersembunyi/i });
                
            const chatCount = await chatLocators.count();

            if (chatCount === 0) {
                console.log(`Tidak ada obrolan di tab ${tabName}.`);
                return;
            }

            console.log(`Ditemukan ${chatCount} obrolan di ${tabName}. Memproses maksimal 5 obrolan teratas...`);
            const maxProcess = Math.min(chatCount, 5);

            for (let i = 0; i < maxProcess; i++) {
                try {
                    const chatItem = chatLocators.nth(i);
                    const itemText = await chatItem.innerText();
                    const lines = itemText.split('\n').map(l => l.trim()).filter(Boolean);
                    
                    // Baris pertama biasanya nama pengirim
                    const senderName = lines[0] || 'Pengirim DM';
                    
                    // Baris kedua biasanya preview pesan (contoh: "min aik pdam di btn tanjung pura dah 3 ha...")
                    let previewText = lines[1] || '';
                    previewText = previewText.split('·')[0].split('...')[0].trim(); // Ambil bagian awalnya saja

                    // Klik obrolan untuk membukanya
                    await chatItem.click({ force: true });
                    await page.waitForTimeout(1500); // Tunggu pesan dimuat

                    // Ekstrak semua gelembung pesan di panel kanan
                    // Instagram selalu menggunakan dir="auto" untuk teks pesan
                    // Kita juga memeriksa CSS flex/align parent-nya untuk membuang pesan dari kita sendiri (yang berada di kanan / flex-end)
                    const allMessages = await page.locator('div[dir="auto"]').evaluateAll(els => {
                        return els.map(e => {
                            let isMine = false;
                            let current = e.parentElement;
                            for (let i = 0; i < 8; i++) {
                                if (!current) break;
                                const style = window.getComputedStyle(current);
                                if (style.alignSelf === 'flex-end') { isMine = true; break; }
                                if (style.flexDirection === 'row' && style.justifyContent === 'flex-end') { isMine = true; break; }
                                if (style.flexDirection === 'column' && style.alignItems === 'flex-end') { isMine = true; break; }
                                current = current.parentElement;
                            }
                            if (isMine) return null; // Abaikan pesan balasan admin
                            return e.innerText.trim();
                        }).filter(Boolean);
                    });

                    // Ambil 5 pesan terakhir untuk memastikan tidak ada aduan beruntun yang terlewat
                    const recentMessages = allMessages.slice(-5);
                    let foundValid = false;

                    for (const msg of recentMessages) {
                        if (isValidPostMessage(msg)) {
                            const fingerprint = makeFingerprint(senderName, msg);
                            if (!seenKeys.has(fingerprint)) {
                                seenKeys.add(fingerprint);
                                results.push({
                                    notification_text: `Pesan DM dari ${senderName} (Tab: ${tabName})`,
                                    sender: senderName,
                                    message_type: 'dm',
                                    post_message: normalize(msg),
                                    post_link: page.url()
                                });
                                foundValid = true;
                            }
                        }
                    }

                    if (!foundValid) {
                        console.log(`Pesan terbaru dari ${senderName} diabaikan (spam/sapaan pendek/sudah diproses).`);
                    }
                } catch (e) {
                    console.log(`Gagal memproses obrolan ke-${i} di ${tabName}: ${e.message}`);
                }
            }
        };

        // FASE 1: AUTO-ACCEPT MESSAGE REQUESTS
        console.log('\n--- FASE 1: Memeriksa Permintaan Pesan (Requests) ---');
        console.log('Membuka halaman Message Requests...');
        await page.goto('https://www.instagram.com/direct/requests/', { waitUntil: 'networkidle' });
        await page.waitForTimeout(1500);
        
        // Cek apakah ada daftar pesan (jika kosong biasanya tampil icon "Message requests")
        const reqLocators = page.locator('a[href^="/direct/t/"], div[role="button"], div[role="listitem"]')
                .filter({ hasText: '·' })
                .filter({ hasNotText: /Hidden Requests|Permintaan Tersembunyi/i });
                
            let reqCount = await reqLocators.count();
            if (reqCount > 0) {
                const maxReq = Math.min(reqCount, 5);
                
                for (let i = 0; i < maxReq; i++) {
                    try {
                        // Selalu klik elemen pertama karena list akan bergeser naik setiap kali kita accept pesan
                        if (await reqLocators.count() === 0) break;
                        
                        await reqLocators.first().click({ force: true });
                        await page.waitForTimeout(1500);
                        
                        // Cari dan klik tombol Accept (Terima)
                        const acceptBtn = page.locator('div[role="button"], button').filter({ hasText: /^Accept$|^Terima$/i });
                        if (await acceptBtn.count() > 0) {
                            console.log(`Mengklik Accept untuk permintaan ke-${i+1}...`);
                            await acceptBtn.first().click({ force: true });
                            await page.waitForTimeout(1000); // Tunggu modal pilihan muncul
                            
                            // Pilih folder "General" (Umum) pada modal
                            const moveGeneralBtn = page.locator('button, div[role="button"], div[role="dialog"] span').filter({ hasText: /^General$|^Umum$/i }).filter({ hasNotText: 'Settings' });
                            if (await moveGeneralBtn.count() > 0) {
                                console.log(`Memindahkan pesan ke tab General...`);
                                await moveGeneralBtn.first().click({ force: true });
                                await page.waitForTimeout(1000);
                            } else {
                                console.log(`Gagal menemukan tombol General di modal. Melewati...`);
                            }
                        } else {
                            console.log(`Tidak menemukan tombol Accept. Mungkin format chat berbeda.`);
                        }
                    } catch (e) {
                        console.log(`Gagal meng-accept permintaan ke-${i+1}: ${e.message}`);
                    }
                }
            } else {
                console.log('Tidak ada pesan baru di tab Requests.');
            }
        console.log('\n--- FASE 2: Mengekstrak Aduan dari General ---');
        console.log('Kembali ke halaman Inbox Utama...');
        await page.goto('https://www.instagram.com/direct/inbox/', { waitUntil: 'networkidle' });
        await page.waitForTimeout(1500);

        console.log('Mencari tab General (Umum)...');
        const generalTabLocators = page.locator('text=/General|Umum/i').filter({ hasNotText: 'Settings' });
        
        if (await generalTabLocators.count() > 0) {
            await generalTabLocators.first().click({ force: true });
            await processChatList('General/Umum');
        } else {
            console.log('Tab General (Umum) tidak ditemukan. Pastikan Anda sudah memindahkan pesan ke tab General.');
        }

        console.log('\n===== HASIL EKSTRAKSI DM =====\n');
        console.log(JSON.stringify(results, null, 4));

    } catch (error) {
        console.error("Fatal Error:", error.message);
    }

    await browser.close();
})();
