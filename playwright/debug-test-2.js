const { chromium } = require('playwright');

(async () => {
    const browser = await chromium.launch({ headless: false });
    const context = await browser.newContext({ storageState: 'instagram-session.json' });
    const page = await context.newPage();

    console.log('Membuka Instagram...');
    await page.goto('https://www.instagram.com/', { waitUntil: 'networkidle' });
    await page.waitForTimeout(3000);

    // 1. Coba ke halaman Tagged
    console.log('Mencari link Profile...');
    // Cari elemen a yang href-nya /username/ dan berisi gambar profil atau teks Profile
    // Cara termudah, ambil semua link di sidebar, cari yang link-nya ke profil
    const links = await page.locator('a[role="link"]').evaluateAll(els => els.map(e => e.href));
    
    // Asumsi: link profil biasanya adalah link terakhir di sidebar utama sebelum "More"
    console.log('Semua link di halaman:', links.filter(l => l.startsWith('https://www.instagram.com/')).slice(0, 15));
    
    // Atau langsung goto /smd.kmc/tagged/
    console.log('Langsung navigasi ke https://www.instagram.com/smd.kmc/tagged/');
    await page.goto('https://www.instagram.com/smd.kmc/tagged/', { waitUntil: 'networkidle' });
    await page.waitForTimeout(4000);

    // Ambil semua post link
    const postLinks = await page.locator('a[href*="/p/"], a[href*="/reel/"]').evaluateAll(els => els.map(e => e.href));
    // Unik
    const uniquePosts = [...new Set(postLinks)];
    console.log('Ditemukan post di Tagged:', uniquePosts);

    // 2. Coba URL /notifications
    console.log('Mencoba buka URL /notifications...');
    await page.goto('https://www.instagram.com/notifications', { waitUntil: 'networkidle' });
    await page.waitForTimeout(4000);
    
    console.log('URL setelah buka /notifications:', page.url());
    const locators = page.locator('text=/menyebut|menandai|mentioned|tagged|comment/i');
    const count = await locators.count();
    console.log('Jumlah elemen teks notifikasi ditemukan:', count);

    for (let i = 0; i < Math.min(count, 5); i++) {
        console.log(`[${i}]`, await locators.nth(i).innerText());
    }

    await browser.close();
})();
