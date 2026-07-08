const { chromium } = require('playwright');
const fs = require('fs');

(async () => {
    const browser = await chromium.launch({ headless: false });
    const context = await browser.newContext({ storageState: 'instagram-session.json' });
    const page = await context.newPage();

    // Buat/Bersihkan file log
    fs.writeFileSync('playwright-clicks.log', '=== LOG KLIK MANUAL ===\n');

    // Buat fungsi yang bisa dipanggil dari dalam browser untuk mencatat log
    await page.exposeFunction('catatKlik', (data) => {
        const logLine = `\n[KLIK] Elemen: ${data.tag}\nClass: ${data.className}\nRole: ${data.role}\nText: ${data.text}\nHTML: ${data.html}\n`;
        console.log(logLine);
        fs.appendFileSync('playwright-clicks.log', logLine);
    });

    // Suntikkan script ke browser untuk mendengarkan setiap klik
    await page.addInitScript(() => {
        document.addEventListener('click', (e) => {
            // Cari elemen yang paling masuk akal diklik (link atau div pembungkus)
            const target = e.target.closest('a, div[role="listitem"], div[role="button"], div[role="tab"]') || e.target;
            
            const data = {
                tag: target.tagName,
                className: target.className,
                role: target.getAttribute('role') || 'null',
                text: (target.innerText || target.textContent || '').substring(0, 100).replace(/\n/g, ' '),
                html: target.outerHTML.substring(0, 150) // Ambil cuplikan HTML-nya
            };
            
            window.catatKlik(data);
        }, true);
    });

    console.log('Membuka Instagram...');
    await page.goto('https://www.instagram.com/direct/inbox/');
    
    console.log('\n=========================================');
    console.log('BROWSER TERBUKA. SILAKAN LAKUKAN KLIK MANUAL!');
    console.log('Buka tab Requests, klik pesan dari user, dll.');
    console.log('Semua klik Anda akan direkam dan dianalisis.');
    console.log('Jika sudah selesai, tutup browser Chrome-nya.');
    console.log('=========================================\n');

    // Tunggu sampai browser ditutup oleh user
    await new Promise(resolve => {
        browser.on('disconnected', resolve);
    });

    console.log('Browser ditutup. Rekaman tersimpan di playwright-clicks.log');
})();
