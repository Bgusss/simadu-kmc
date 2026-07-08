const { chromium } = require('playwright');

(async () => {

    const browser = await chromium.launch({
        headless: false
    });

    const context = await browser.newContext({
        storageState: 'facebook-session.json'
    });

    const page = await context.newPage();

    console.log('Membuka Facebook...');

    await page.goto(
        'https://www.facebook.com/notifications',
        {
            waitUntil: 'networkidle'
        }
    );

    console.log('Menunggu notifikasi dimuat...');

    await page.waitForTimeout(5000);

    console.log('\n===== NOTIFIKASI YANG MENGANDUNG "MENYEBUT" =====\n');

    const notifications = await page.locator('a').evaluateAll(elements => {

        return elements
            .map(el => ({

                text: el.innerText
                    ? el.innerText.trim()
                    : '',

                href: el.href

            }))
            .filter(item =>
                item.text &&
                item.text.toLowerCase().includes('menyebut')
            );

    });

    if (notifications.length === 0) {

        console.log(
            'Tidak ditemukan notifikasi yang mengandung kata "menyebut".'
        );

    } else {

        notifications.forEach((notif, index) => {

            console.log(`\n[${index + 1}]`);

            console.log(
                'Teks :',
                notif.text
            );

            console.log(
                'Link :',
                notif.href
            );

        });

    }

    console.log(
        '\n==============================================\n'
    );

    console.log(
        '\n===== ISI HALAMAN =====\n'
    );

    const bodyText = await page
        .locator('body')
        .innerText();

    console.log(bodyText);

    console.log(
        '\n=======================\n'
    );

    console.log(
        'Cari apakah ada teks seperti:'
    );

    console.log(
        '"menyebut Anda di komentar mereka"'
    );

    console.log(
        '"menyebut Anda dalam postingan mereka"'
    );

    await browser.close();

})();