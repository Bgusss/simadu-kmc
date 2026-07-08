const { chromium } = require('playwright');

(async () => {

    const browser = await chromium.launch({
        headless: false
    });

    const page = await browser.newPage();

    // Pergi ke halaman login Instagram
    await page.goto('https://www.instagram.com/');

    console.log('Silakan login ke Instagram.');
    console.log('Jika ada prompt "Save your login info?", Anda bisa klik "Save Info" atau "Not Now".');
    console.log('Setelah login berhasil dan berada di halaman utama, tekan ENTER di terminal.');

    process.stdin.once('data', async () => {
        // Simpan sesi login
        await page.context().storageState({
            path: 'instagram-session.json'
        });

        console.log('Session berhasil disimpan ke instagram-session.json.');

        await browser.close();
        process.exit(0);
    });

})();
