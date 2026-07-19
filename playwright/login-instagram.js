const { chromium } = require('playwright');
const path = require('path');

(async () => {

    const SESSION_FILE = path.resolve(__dirname, 'instagram-session.json');

    const browser = await chromium.launch({
        headless: false,
        args: [
            '--disable-blink-features=AutomationControlled',
            '--disable-features=Translate',
            '--disable-translate',
            '--lang=id-ID'
        ]
    });

    const context = await browser.newContext({
        locale: 'id-ID',
        timezoneId: 'Asia/Pontianak',
        viewport: { width: 1920, height: 1080 },
        extraHTTPHeaders: {
            'Accept-Language': 'id-ID,id;q=0.9'
        }
    });

    await context.addInitScript(() => {
        Object.defineProperty(navigator, 'languages', { get: () => ['id-ID', 'id'] });
        Object.defineProperty(navigator, 'language', { get: () => 'id-ID' });
    });

    const page = await context.newPage();

    await page.goto('https://www.instagram.com/accounts/login/');

    console.log('Silakan login Instagram.');
    console.log('Selesaikan verifikasi email/SMS jika diminta.');
    console.log('Pastikan sudah masuk ke halaman utama Instagram.');
    console.log('');
    console.log('Setelah login berhasil, tekan ENTER di terminal.');

    process.stdin.once('data', async () => {

        await context.storageState({
            path: SESSION_FILE
        });

        console.log(`Session Instagram berhasil disimpan di: ${SESSION_FILE}`);

        await browser.close();

    });

})();
