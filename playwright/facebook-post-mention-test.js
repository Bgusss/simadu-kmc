const { chromium } = require('playwright');

(async () => {

    const browser = await chromium.launch({

        headless: false

    });

    const context = await browser.newContext({

        storageState: 'facebook-session.json'

    });

    const page = await context.newPage();

    console.log(
        'Membuka notifikasi Facebook...'
    );

    await page.goto(

        'https://web.facebook.com/notifications',

        {
            waitUntil: 'networkidle'
        }

    );

    await page.waitForTimeout(5000);

    const notifications = await page.$$eval(

        'a[href*="notif_id="]',

        elements => {

            return elements.map(el => ({

                text:

                    el.innerText,

                href:

                    el.href

            }));

        }

    );

    console.log(

        JSON.stringify(

            notifications,

            null,

            4

        )

    );

})();