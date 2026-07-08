const { chromium } = require('playwright');

(async () => {

    const browser = await chromium.launch({
        headless: false
    });

    const context = await browser.newContext({
        storageState: 'facebook-session.json'
    });

    const page = await context.newPage();

    console.log('Membuka Facebook Notifications...');

    await page.goto(
        'https://www.facebook.com/notifications',
        {
            waitUntil: 'networkidle'
        }
    );

    console.log('Menunggu notifikasi dimuat...');

    await page.waitForTimeout(5000);

    const notifications = await page.locator('a').evaluateAll(elements => {

        return elements
            .map(el => {

                const text = el.innerText
                    ? el.innerText.trim()
                    : '';

                const href = el.href || '';

                const match = href.match(
                    /comment_id=(\d+)/
                );

                return {

                    notification_text: text,

                    comment_link: href,

                    comment_id: match
                        ? match[1]
                        : null,

                };

            })
            .filter(item =>

                item.comment_link.includes(
                    'notif_t=comment_mention'
                )

            );

    });

    console.log('\n===== HASIL JSON =====\n');

    console.log(
        JSON.stringify(
            notifications,
            null,
            4
        )
    );

    console.log('\n======================\n');

    await page.waitForTimeout(10000);

    await browser.close();

})();