const { chromium } = require('@playwright/test');

(async () => {
  const browser = await chromium.launch({ 
    headless: false,
    slowMo: 1000 
  });
  const context = await browser.newContext();
  const page = await context.newPage();

  try {
    console.log('Navigating to login page...');
    await page.goto('http://127.0.0.1:8001/login');
    
    console.log('Waiting for login form...');
    await page.waitForSelector('input[name="login"]', { timeout: 5000 });
    
    console.log('Filling in credentials...');
    // Fill in username/email field
    await page.fill('input[name="login"]', 'koe.kimsou.bat');
    
    // Fill in password field
    await page.fill('input[name="password"]', 'admin123');
    
    console.log('Clicking login button...');
    // Click the login button
    await page.click('button[type="submit"]');
    
    // Wait for navigation or error
    await page.waitForTimeout(3000);
    
    // Check current URL
    const currentUrl = page.url();
    console.log('Current URL after login:', currentUrl);
    
    // Check for any error messages
    const errorElement = await page.$('.text-red-600, .alert-danger, .error');
    if (errorElement) {
      const errorText = await errorElement.textContent();
      console.log('Error message found:', errorText);
    }
    
    // Check if we're on dashboard (successful login)
    if (currentUrl.includes('dashboard') || currentUrl.includes('home')) {
      console.log('✅ Login successful!');
    } else if (currentUrl.includes('login')) {
      console.log('❌ Still on login page - authentication may have failed');
      
      // Try to capture any validation messages
      const pageContent = await page.content();
      if (pageContent.includes('credentials')) {
        console.log('Invalid credentials error detected');
      }
    }
    
    // Take a screenshot for debugging
    await page.screenshot({ path: 'login-test-result.png' });
    console.log('Screenshot saved as login-test-result.png');
    
  } catch (error) {
    console.error('Test failed:', error);
    await page.screenshot({ path: 'login-test-error.png' });
  } finally {
    await browser.close();
  }
})();