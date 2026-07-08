const { chromium } = require('playwright');

(async () => {

    const browser = await chromium.launch({
        headless: false
    });

    const context = await browser.newContext({
        storageState: 'facebook-session.json'
    });

    const page = await context.newPage();

    await page.goto(
        'https://web.facebook.com/notification/disaggregate/mentions/1780888617395828/?notif_id=1780888617395828&notif_t=comment_mention&ref=notif',
        {
            waitUntil: 'networkidle'
        }
    );

    await page.waitForTimeout(5000);

    const links = await page.locator('a').evaluateAll(elements => {

        return elements
            .map(el => ({
                text: el.innerText,
                href: el.href
            }))
            .filter(item =>
                item.text &&
                item.text.toLowerCase().includes('mentioned')
            );

    });

    console.log(links);

    await browser.close();

})();