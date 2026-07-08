const { chromium } = require('playwright');
const path = require('path');

(async () => {
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  await page.setViewportSize({ width: 1280, height: 900 });
  
  try {
    console.log('Navigating to http://127.0.0.1:8082/ticketing...');
    await page.goto('http://127.0.0.1:8082/ticketing', { waitUntil: 'networkidle' });
    await page.waitForTimeout(2000);
    
    // Desktop screenshot
    const deskPath = 'C:\\Users\\Bguss2\\.gemini\\antigravity\\brain\\04594d95-4b36-4191-b251-96bd964cdbf9\\ticketing_desktop.png';
    console.log(`Saving desktop screenshot to ${deskPath}...`);
    await page.screenshot({ path: deskPath });
    
    // Mobile screenshot
    await page.setViewportSize({ width: 375, height: 812 });
    await page.waitForTimeout(1000);
    const mobPath = 'C:\\Users\\Bguss2\\.gemini\\antigravity\\brain\\04594d95-4b36-4191-b251-96bd964cdbf9\\ticketing_mobile.png';
    console.log(`Saving mobile screenshot to ${mobPath}...`);
    await page.screenshot({ path: mobPath });
    
    console.log('Screenshots taken successfully!');
  } catch (error) {
    console.error('Error during screenshot capture:', error);
  } finally {
    await browser.close();
  }
})();
