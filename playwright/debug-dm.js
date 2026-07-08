const { chromium } = require('playwright');

(async () => {
    const browser = await chromium.launch({ headless: false });
    const context = await browser.newContext({ storageState: 'instagram-session.json' });
    const page = await context.newPage();

    console.log('Membuka Instagram Inbox...');
    await page.goto('https://www.instagram.com/direct/inbox/', { waitUntil: 'networkidle' });
    await page.waitForTimeout(6000);

    await page.getByRole('button', { name: 'Not Now' }).click({ force: true }).catch(() => {});
    await page.getByRole('button', { name: 'Lain Kali' }).click({ force: true }).catch(() => {});

    const allLinks = await page.locator('a').evaluateAll(els => els.map(e => e.href));
    console.log('Semua link di halaman inbox:', allLinks);

    // Ambil screenshot
    await page.screenshot({ path: 'ig-debug-dm.png' });
    
    await browser.close();
})();
