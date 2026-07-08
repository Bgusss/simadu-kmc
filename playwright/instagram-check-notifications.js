const { chromium } = require('playwright');

(async () => {

    const browser = await chromium.launch({
        headless: false
    });

    const context = await browser.newContext({
        storageState: 'instagram-session.json'
    });

    const page = await context.newPage();

    console.log('Membuka Instagram...');

    // Buka halaman utama Instagram
    await page.goto(
        'https://www.instagram.com/',
        {
            waitUntil: 'networkidle'
        }
    );

    console.log('Mencari tombol Notifikasi...');

    // Tunggu sebentar agar elemen DOM termuat sepenuhnya
    await page.waitForTimeout(3000);

    try {
        // Karena struktur DOM Instagram rumit dan sering menyembunyikan elemen aslinya,
        // kita akan menggunakan eksekusi DOM langsung (evaluate) untuk mengklik ikon atau bungkusannya
        const svgs = [
            'svg[aria-label="Notifications"]',
            'svg[aria-label="Notifikasi"]'
        ];

        let clicked = false;
        for (const svgSel of svgs) {
            const count = await page.locator(svgSel).count();
            if (count > 0) {
                // Cari elemen pembungkus terdekat (a atau div yang bisa diklik) dan panggil metode DOM .click()
                await page.locator(svgSel).first().evaluate(node => {
                    const clickable = node.closest('a') || node.closest('div[role="button"]') || node.closest('div[role="link"]') || node.parentElement;
                    clickable.click();
                });
                clicked = true;
                console.log(`Berhasil mengklik tombol (via DOM) dari ikon: ${svgSel}`);
                break;
            }
        }

        if (!clicked) {
            console.log('Fallback: Mencari teks Notifications dan mengklik elemen utamanya...');
            await page.getByText('Notifications', { exact: true }).first().evaluate(node => {
                const clickable = node.closest('a') || node.closest('div[role="button"]') || node.parentElement;
                clickable.click();
            });
        }
    } catch (e) {
        console.log('Peringatan: Gagal mengklik tombol Notifikasi. Error:', e.message);
    }

    console.log('Menunggu notifikasi dimuat...');
    
    // Tunggu flyout panel / halaman notifikasi terbuka dan memuat data
    await page.waitForTimeout(5000);

    console.log('\n===== NOTIFIKASI YANG MENGANDUNG "MENYEBUT" ATAU "MENANDAI" =====\n');

    // Mencari elemen dengan teks yang cocok secara case-insensitive menggunakan Locator bawaan Playwright
    // Ini jauh lebih akurat daripada evaluate DOM karena Playwright otomatis mencari node terdalam
    const locators = page.locator('text=/menyebut|menandai|mentioned|tagged/i');
    const count = await locators.count();

    const uniqueNotifications = [];
    const seen = new Set();

    for (let i = 0; i < count; i++) {
        const el = locators.nth(i);
        let text = await el.innerText();
        text = text.replace(/\n/g, ' ').trim();

        // Naik 5 tingkat DOM untuk mendapatkan seluruh bungkus baris notifikasi
        let parent = el;
        for (let j = 0; j < 5; j++) {
            parent = parent.locator('xpath=..');
        }

        // Cari semua link (href) yang ada di baris tersebut
        const links = await parent.locator('a[href]').evaluateAll(elements => elements.map(e => e.href));
        
        let targetHref = 'Tidak ada link post langsung (mungkin di-handle via JS klik baris)';
        
        // Prioritaskan link postingan/reels
        const postLink = links.find(href => href.includes('/p/') || href.includes('/reel/'));
        if (postLink) {
            targetHref = postLink;
        } else if (links.length > 0) {
            targetHref = links[0]; // Setidaknya kasih link ke profil pengirim
        }

        const key = targetHref + '|' + text;
        if (!seen.has(key)) {
            seen.add(key);
            uniqueNotifications.push({ text, href: targetHref });
        }
    }

    if (uniqueNotifications.length === 0) {
        console.log('Tidak ditemukan notifikasi tag/mention dari link yang ada.');
    } else {
        uniqueNotifications.forEach((notif, index) => {
            console.log(`\n[${index + 1}]`);
            console.log('Teks :', notif.text.replace(/\n/g, ' '));
            console.log('Link :', notif.href);
        });
    }

    console.log('\n==============================================\n');

    console.log('\n===== DEBUG: ISI TEKS HALAMAN =====\n');
    
    // Berguna untuk mencari tahu kata kunci yang tepat jika script gagal mendeteksi notifikasi
    const bodyText = await page.locator('body').innerText();
    
    // Kita filter agar tidak terlalu panjang, hanya ambil baris yang mengandung kata kunci
    const lines = bodyText.split('\n');
    const suspiciousLines = lines.filter(line => {
        const l = line.toLowerCase();
        return l.includes('menyebut') || l.includes('menandai') || l.includes('mentioned') || l.includes('tagged');
    });

    if (suspiciousLines.length > 0) {
        console.log('Ditemukan teks berikut di body (mungkin elemen tidak berupa link <a>):');
        suspiciousLines.forEach(l => console.log('-', l));
    } else {
        console.log('(Tidak ada teks mencurigakan di body)');
    }

    console.log('\n=======================\n');

    console.log('Menyimpan screenshot halaman ke ig-debug-notif.png untuk keperluan debug...');
    await page.screenshot({ path: 'ig-debug-notif.png', fullPage: true });

    await browser.close();

})();
