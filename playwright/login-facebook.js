/**
 * Login Facebook — Manual
 * 
 * Membuka browser Facebook agar Anda bisa login secara manual.
 * Setelah login berhasil dan halaman utama Facebook muncul,
 * tekan Enter di terminal untuk menyimpan session.
 * 
 * Cara pakai:
 *   node playwright/login-facebook.js
 */

const { chromium } = require('playwright');
const path = require('path');
const readline = require('readline');

const SESSION_PATH = path.join(__dirname, 'facebook-session.json');

(async () => {
    console.log('🔵 Membuka browser Facebook untuk login manual...');
    console.log('   Silakan login dengan akun Facebook Anda.');
    console.log('   Setelah berhasil login dan halaman utama muncul,');
    console.log('   kembali ke terminal ini dan tekan ENTER.\n');

    const browser = await chromium.launch({
        headless: false,
        args: [
            '--disable-blink-features=AutomationControlled',
            '--no-sandbox'
        ]
    });

    const context = await browser.newContext({
        locale: 'id-ID',
        userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36'
    });

    const page = await context.newPage();
    await page.goto('https://www.facebook.com/', { waitUntil: 'domcontentloaded' });

    // Tunggu user tekan Enter setelah login
    const rl = readline.createInterface({ input: process.stdin, output: process.stdout });
    await new Promise(resolve => rl.question('✅ Tekan ENTER setelah berhasil login Facebook...', resolve));
    rl.close();

    // Simpan session
    const storage = await context.storageState();
    require('fs').writeFileSync(SESSION_PATH, JSON.stringify(storage, null, 2));
    console.log('\n💾 Session Facebook tersimpan di: ' + SESSION_PATH);

    await browser.close();
    console.log('🎉 Selesai! Scraper Facebook siap digunakan.');
})();
