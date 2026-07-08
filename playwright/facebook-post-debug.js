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
        'https://web.facebook.com/mentions/1780890552311938/?notif_id=1780890552311938&notif_t=mention&ref=notif',
        {
            waitUntil: 'networkidle'
        }
    );

    await page.waitForTimeout(8000);

    // Kandidat permalink postingan
    const weirdLinks = await page.locator(
        'a[href*="#?"]'
    ).all();

    console.log(
        '\nJumlah kandidat:',
        weirdLinks.length
    );

    for (

        let i = 0;

        i < Math.min(
            weirdLinks.length,
            2
        );

        i++

    ) {

        console.log(
            `\n========== Kandidat ${i + 1} ==========`
        );

        try {

            const href = await weirdLinks[i]
                .getAttribute('href');

            console.log(
                'HREF:',
                href
            );

            const parentText =
                await weirdLinks[i]
                    .locator('xpath=ancestor::div[5]')
                    .innerText();

            console.log(
                '\n===== PARENT TEXT =====\n'
            );

            console.log(
                parentText
            );

            console.log(
                '\n=======================\n'
            );

            await weirdLinks[i].click();

            await page.waitForTimeout(
                2000
            );

            console.log('\n===== HTML POST =====\n');

            const html = await page.content();

            require('fs').writeFileSync(
                `debug-post-${i + 1}.html`,
                html
            );

            console.log(
                `debug-post-${i + 1}.html berhasil disimpan`
            );

            console.log(
                'URL:',
                page.url()
            );

            console.log(
                '\n===== CARI ISI POSTINGAN =====\n'
            );

            const messages = await page.$$eval(
                'div[dir="auto"]',
                elements => elements
                    .map(el => el.innerText.trim())
                    .filter(text =>
                        text.length > 5 &&
                        text.length < 500
                    )
            );

            let postMessage = '';

            if (messages.length > 0) {

                postMessage = messages[0];

            }

            console.log(
                'PESAN:',
                postMessage
            );

            console.log(
                '\n===============================\n'
            );

            await page.goBack();

            await page.waitForTimeout(
                1000
            );

        } catch (error) {

            console.log(
                'ERROR:',
                error.message
            );

        }

    }

    await browser.close();

})();