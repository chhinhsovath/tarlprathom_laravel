import puppeteer from 'puppeteer';

(async () => {
  const browser = await puppeteer.launch({ 
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox']
  });
  
  try {
    const page = await browser.newPage();
    
    console.log('Testing TaRL application...\n');
    
    // Test 1: Check if main URL loads
    console.log('1. Testing main URL: https://plp.moeys.gov.kh/tarl/');
    await page.goto('https://plp.moeys.gov.kh/tarl/', { 
      waitUntil: 'networkidle2',
      timeout: 30000 
    });
    
    const title = await page.title();
    const url = page.url();
    
    console.log(`   ✓ Page loaded successfully`);
    console.log(`   - Title: ${title}`);
    console.log(`   - Final URL: ${url}`);
    
    // Test 2: Check for key elements
    console.log('\n2. Checking for key page elements:');
    
    // Check for assessment chart
    const hasChart = await page.$('#assessmentChart') !== null;
    console.log(`   ${hasChart ? '✓' : '✗'} Assessment chart found`);
    
    // Check for subject buttons
    const hasKhmerBtn = await page.$('#khmerBtn') !== null;
    const hasMathBtn = await page.$('#mathBtn') !== null;
    console.log(`   ${hasKhmerBtn ? '✓' : '✗'} Khmer button found`);
    console.log(`   ${hasMathBtn ? '✓' : '✗'} Math button found`);
    
    // Check for table
    const hasTable = await page.$('table') !== null;
    console.log(`   ${hasTable ? '✓' : '✗'} Results table found`);
    
    // Test 3: Check API endpoint
    console.log('\n3. Testing API endpoint:');
    const apiResponse = await page.evaluate(async () => {
      try {
        const response = await fetch('/tarl/api/assessment-data?subject=khmer');
        return {
          status: response.status,
          ok: response.ok,
          contentType: response.headers.get('content-type')
        };
      } catch (error) {
        return { error: error.message };
      }
    });
    
    if (apiResponse.error) {
      console.log(`   ✗ API Error: ${apiResponse.error}`);
    } else {
      console.log(`   ${apiResponse.ok ? '✓' : '✗'} API responded with status: ${apiResponse.status}`);
      console.log(`   - Content-Type: ${apiResponse.contentType}`);
    }
    
    // Test 4: Check for JavaScript errors
    console.log('\n4. Checking for JavaScript errors:');
    const jsErrors = [];
    page.on('pageerror', error => jsErrors.push(error.message));
    await page.reload();
    await page.waitForTimeout(2000);
    
    if (jsErrors.length === 0) {
      console.log('   ✓ No JavaScript errors detected');
    } else {
      console.log('   ✗ JavaScript errors found:');
      jsErrors.forEach(error => console.log(`     - ${error}`));
    }
    
    // Test 5: Performance metrics
    console.log('\n5. Performance metrics:');
    const metrics = await page.metrics();
    console.log(`   - DOM Content Loaded: ${Math.round(metrics.DOMContentLoaded)}ms`);
    console.log(`   - Page Load Complete: ${Math.round(metrics.Load)}ms`);
    
    // Take screenshot
    await page.screenshot({ 
      path: 'tarl-test-screenshot.png',
      fullPage: true 
    });
    console.log('\n✓ Screenshot saved as tarl-test-screenshot.png');
    
    console.log('\n✅ All tests completed!');
    
  } catch (error) {
    console.error('\n❌ Test failed:', error.message);
  } finally {
    await browser.close();
  }
})();