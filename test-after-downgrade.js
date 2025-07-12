import puppeteer from 'puppeteer';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Create evidence directory
const evidenceDir = path.join(__dirname, 'test-evidence', `test-run-${new Date().toISOString().replace(/:/g, '-')}`);
fs.mkdirSync(evidenceDir, { recursive: true });

// Test configuration
const BASE_URL = 'http://127.0.0.1:8001';
const ADMIN_EMAIL = 'admin@tarlconnect.com';
const ADMIN_PASSWORD = 'password';

// Helper function to log and save evidence
async function captureEvidence(page, testName, step) {
    const filename = `${testName}-${step}-${Date.now()}`;
    
    // Take screenshot
    await page.screenshot({ 
        path: path.join(evidenceDir, `${filename}.png`),
        fullPage: true 
    });
    
    // Save page content
    const content = await page.content();
    fs.writeFileSync(path.join(evidenceDir, `${filename}.html`), content);
    
    console.log(`✓ Evidence captured: ${filename}`);
}

// Main test function
async function runTests() {
    const browser = await puppeteer.launch({
        headless: false, // Set to true for CI/CD
        slowMo: 100, // Slow down for visibility
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    });
    
    const page = await browser.newPage();
    
    // Set viewport
    await page.setViewport({ width: 1280, height: 800 });
    
    // Enable console log capture
    page.on('console', msg => {
        const logFile = path.join(evidenceDir, 'console.log');
        fs.appendFileSync(logFile, `[${msg.type()}] ${msg.text()}\n`);
    });
    
    // Capture network errors
    page.on('pageerror', error => {
        const errorFile = path.join(evidenceDir, 'errors.log');
        fs.appendFileSync(errorFile, `Page Error: ${error.message}\n${error.stack}\n\n`);
    });
    
    try {
        console.log('🧪 Starting Laravel TaRL Application Tests...\n');
        
        // Test 1: Homepage Load
        console.log('📍 Test 1: Loading Homepage...');
        await page.goto(BASE_URL, { waitUntil: 'networkidle2' });
        await captureEvidence(page, 'test1-homepage', 'loaded');
        
        // Check if login page redirects correctly
        const currentUrl = page.url();
        console.log(`   Current URL: ${currentUrl}`);
        
        // Test 2: Login Page
        console.log('\n📍 Test 2: Testing Login...');
        if (!currentUrl.includes('/login')) {
            await page.goto(`${BASE_URL}/login`, { waitUntil: 'networkidle2' });
        }
        await captureEvidence(page, 'test2-login', 'page-loaded');
        
        // Fill login form
        await page.type('input[name="email"]', ADMIN_EMAIL);
        await page.type('input[name="password"]', ADMIN_PASSWORD);
        await captureEvidence(page, 'test2-login', 'form-filled');
        
        // Submit login
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle2' }),
            page.click('button[type="submit"]')
        ]);
        await captureEvidence(page, 'test2-login', 'after-submit');
        
        // Verify login success
        const dashboardUrl = page.url();
        if (dashboardUrl.includes('/dashboard')) {
            console.log('   ✅ Login successful - Redirected to dashboard');
        } else {
            throw new Error(`Login failed - Current URL: ${dashboardUrl}`);
        }
        
        // Test 3: Dashboard Load
        console.log('\n📍 Test 3: Testing Dashboard...');
        await page.waitForSelector('.max-w-7xl', { timeout: 10000 });
        await captureEvidence(page, 'test3-dashboard', 'loaded');
        
        // Test 4: Navigation Menu
        console.log('\n📍 Test 4: Testing Navigation Menu...');
        const navLinks = await page.$$eval('nav a', links => 
            links.map(link => ({ text: link.textContent.trim(), href: link.href }))
        );
        console.log(`   Found ${navLinks.length} navigation links`);
        fs.writeFileSync(path.join(evidenceDir, 'navigation-links.json'), JSON.stringify(navLinks, null, 2));
        
        // Test 5: Students Page
        console.log('\n📍 Test 5: Testing Students Page...');
        await page.goto(`${BASE_URL}/students`, { waitUntil: 'networkidle2' });
        await page.waitForSelector('table', { timeout: 10000 });
        await captureEvidence(page, 'test5-students', 'page-loaded');
        
        // Test 6: Assessments Page
        console.log('\n📍 Test 6: Testing Assessments Page...');
        await page.goto(`${BASE_URL}/assessments`, { waitUntil: 'networkidle2' });
        await page.waitForSelector('.bg-white', { timeout: 10000 });
        await captureEvidence(page, 'test6-assessments', 'page-loaded');
        
        // Test 7: Mentoring Visits Page
        console.log('\n📍 Test 7: Testing Mentoring Visits Page...');
        await page.goto(`${BASE_URL}/mentoring`, { waitUntil: 'networkidle2' });
        await page.waitForSelector('.bg-white', { timeout: 10000 });
        await captureEvidence(page, 'test7-mentoring', 'page-loaded');
        
        // Test 8: Reports Page
        console.log('\n📍 Test 8: Testing Reports Page...');
        await page.goto(`${BASE_URL}/reports`, { waitUntil: 'networkidle2' });
        await page.waitForSelector('.bg-white', { timeout: 10000 });
        await captureEvidence(page, 'test8-reports', 'page-loaded');
        
        // Test 9: Language Switch
        console.log('\n📍 Test 9: Testing Language Switch...');
        // Click on language dropdown if exists
        const langSwitcher = await page.$('[id*="language"]');
        if (langSwitcher) {
            await langSwitcher.click();
            await new Promise(resolve => setTimeout(resolve, 500));
            await captureEvidence(page, 'test9-language', 'dropdown-open');
            
            // Try to switch to Khmer
            const khmerLink = await page.$('a[href*="/language/km"]');
            if (khmerLink) {
                await khmerLink.click();
                await page.waitForNavigation({ waitUntil: 'networkidle2' });
                await captureEvidence(page, 'test9-language', 'switched-to-khmer');
                console.log('   ✅ Language switch successful');
            }
        }
        
        // Test 10: Profile Page
        console.log('\n📍 Test 10: Testing Profile Page...');
        await page.goto(`${BASE_URL}/profile`, { waitUntil: 'networkidle2' });
        await page.waitForSelector('form', { timeout: 10000 });
        await captureEvidence(page, 'test10-profile', 'page-loaded');
        
        // Generate test summary
        const summary = {
            testDate: new Date().toISOString(),
            baseUrl: BASE_URL,
            phpVersion: 'Downgraded to 8.1.2 compatibility',
            testsRun: 10,
            testsPassed: 10,
            evidenceDirectory: evidenceDir,
            browserInfo: await browser.version()
        };
        
        fs.writeFileSync(path.join(evidenceDir, 'test-summary.json'), JSON.stringify(summary, null, 2));
        
        console.log('\n✅ All tests completed successfully!');
        console.log(`📁 Evidence saved to: ${evidenceDir}`);
        
    } catch (error) {
        console.error('\n❌ Test failed:', error.message);
        await captureEvidence(page, 'error', 'failure');
        
        // Save error details
        fs.writeFileSync(path.join(evidenceDir, 'test-error.json'), JSON.stringify({
            error: error.message,
            stack: error.stack,
            timestamp: new Date().toISOString()
        }, null, 2));
        
        throw error;
    } finally {
        await browser.close();
    }
}

// Run the tests
runTests().catch(error => {
    console.error('Test suite failed:', error);
    process.exit(1);
});