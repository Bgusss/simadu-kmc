const { chromium } = require('playwright');

(async () => {

    const browser = await chromium.launch({
        headless: false
    });

    const context = await browser.newContext({
        storageState: 'facebook-session.json'
    });

    const page = await context.newPage();

    const notifUrl =
        'https://web.facebook.com/notification/disaggregate/mentions/1780888617395828/?notif_id=1780888617395828&notif_t=comment_mention&ref=notif';

    console.log('Membuka notifikasi...');

    await page.goto(
        notifUrl,
        {
            waitUntil: 'networkidle'
        }
    );

    await page.waitForTimeout(5000);

    console.log('\n===== URL AKHIR =====\n');

    console.log(page.url());

    console.log('\n===== ISI HALAMAN =====\n');

    console.log(
        await page.locator('body').innerText()
    );

    console.log('\n======================\n');

})();