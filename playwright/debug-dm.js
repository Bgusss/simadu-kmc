const { chromium } = require('playwright');
const path = require('path');
const fs = require('fs');

(async () => {
    const SESSION_FILE = path.resolve(__dirname, 'instagram-session.json');

    const browser = await chromium.launch({ headless: false });
    const context = await browser.newContext({ storageState: SESSION_FILE, locale: 'id-ID' });
    const page = await context.newPage();
    await page.goto('https://www.instagram.com/direct/inbox/');
    await page.waitForTimeout(5000);

    // Coba klik tab General
    const generalTab = page.locator('div[role="tab"], button, span')
        .filter({ hasText: /^General$|^Umum$/i })
        .filter({ hasNotText: /Settings|Pengaturan/i });

    if (await generalTab.count() > 0) {
        await generalTab.first().click({ force: true });
        await page.waitForTimeout(3000);
    }

    // Klik chat Ryan (yang di dalamnya ada balasan kita: "baik akan segera di tindaklanjuti")
    const chatLocators = page.locator('div[role="button"]').filter({ hasText: /Ryan/ });
    if (await chatLocators.count() > 0) {
        await chatLocators.first().click({ force: true });
        await page.waitForTimeout(4000); 
    }

    console.log("=== COMPARE OUTBOUND VS INBOUND DOM ===");
    const debug = await page.evaluate(() => {
        const elements = Array.from(document.querySelectorAll('span'));
        
        // Cari pesan dari kita (outbound)
        const outbound = elements.find(el => el.innerText && el.innerText.includes('tindaklanjuti') && el.innerText.length < 200);
        // Cari pesan dari Ryan (inbound)
        const inbound = elements.find(el => el.innerText && el.innerText.includes('mati lampu') && el.innerText.length < 200);

        const getPath = (el) => {
            if (!el) return "NOT FOUND";
            let p = [];
            let curr = el;
            for (let i = 0; i < 7; i++) {
                if (curr) {
                    const style = window.getComputedStyle(curr);
                    p.push(curr.tagName + 
                        (curr.getAttribute('role') ? `[role=${curr.getAttribute('role')}]` : '') +
                        `.${curr.className.split(' ')[0]}` +
                        ` {alignSelf:${style.alignSelf}, alignContent:${style.alignContent}, bg:${style.backgroundColor}}`
                    );
                    curr = curr.parentElement;
                }
            }
            return p.join(' -> ');
        };

        return {
            outboundPath: getPath(outbound),
            inboundPath: getPath(inbound)
        };
    });

    console.log(JSON.stringify(debug, null, 2));
    await browser.close();
})();
