const { chromium } = require('playwright');

(async () => {

    const browser = await chromium.launch({
        headless: false
    });

    const page = await browser.newPage();

    await page.goto('https://www.facebook.com/');

    console.log('Silakan login Facebook.');

    console.log('Setelah login berhasil, tekan ENTER di terminal.');

    process.stdin.once('data', async () => {

        await page.context().storageState({
            path: 'facebook-session.json'
        });

        console.log('Session berhasil disimpan.');

        await browser.close();

    });

})();