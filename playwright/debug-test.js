const { chromium } = require('playwright');

(async () => {
    const browser = await chromium.launch({ headless: false });
    const context = await browser.newContext({ storageState: 'instagram-session.json' });
    const page = await context.newPage();

    console.log('Membuka Instagram...');
    await page.goto('https://www.instagram.com/', { waitUntil: 'networkidle' });

    console.log('Klik Notifikasi...');
    await page.waitForTimeout(3000);

    const svgs = ['svg[aria-label="Notifications"]', 'svg[aria-label="Notifikasi"]'];
    for (const svgSel of svgs) {
        if (await page.locator(svgSel).count() > 0) {
            await page.locator(svgSel).first().evaluate(node => {
                const clickable = node.closest('a') || node.closest('div[role="button"]') || node.closest('div[role="link"]') || node.parentElement;
                clickable.click();
            });
            break;
        }
    }

    await page.waitForTimeout(5000);

    console.log('Mencari notifikasi menggunakan Playwright Locator...');
    
    // Cari semua elemen terdalam yang memiliki teks yang cocok
    const locators = page.locator('text=/menyebut|menandai|mentioned|tagged/i');
    const count = await locators.count();
    console.log(`Ditemukan ${count} elemen dengan teks yang cocok.`);

    for (let i = 0; i < count; i++) {
        const el = locators.nth(i);
        const text = await el.innerText();
        console.log(`\nElemen [${i+1}] teks:`, text);

        // Cari elemen parent (naik 5 tingkat)
        let parent = el;
        for (let j = 0; j < 5; j++) {
            parent = parent.locator('xpath=..');
        }

        // Cari semua link dalam parent
        const links = await parent.locator('a[href]').evaluateAll(elements => elements.map(e => e.href));
        console.log('Links di parent:', links);
    }

    await browser.close();
})();
