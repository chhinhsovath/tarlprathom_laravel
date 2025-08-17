import { test, expect } from '@playwright/test';
import { TEST_USERS, login } from './helpers/test-helpers';

const criticalRoutes = [
  { path: '/dashboard', name: 'Dashboard' },
  { path: '/schools', name: 'Schools' },
  { path: '/students', name: 'Students' },
  { path: '/classes', name: 'Classes' },
  { path: '/assessments', name: 'Assessments' },
  { path: '/mentoring', name: 'Mentoring' },
  { path: '/reports', name: 'Reports' },
  { path: '/users', name: 'Users' },
];

test.describe('Error Detection Test', () => {
  test('Admin - Check all critical routes for errors', async ({ page }) => {
    console.log('\n🔍 Error Detection Test - Admin Role');
    console.log('='.repeat(60));
    
    // Login as admin
    await login(page, 'admin');
    console.log('✅ Logged in as Admin');
    
    const errors = [];
    
    for (const route of criticalRoutes) {
      console.log(`\nTesting: ${route.path} - ${route.name}`);
      
      try {
        await page.goto(`http://localhost:8001${route.path}`, {
          waitUntil: 'domcontentloaded',
          timeout: 10000
        });
        
        // Wait for page to settle
        await page.waitForTimeout(1000);
        
        // Check for Laravel error page
        const hasLaravelError = await page.locator('.exception-message, .exception_message').isVisible().catch(() => false);
        if (hasLaravelError) {
          const errorTitle = await page.locator('.exception-title, .exception_title').textContent().catch(() => 'Unknown');
          const errorMessage = await page.locator('.exception-message, .exception_message').textContent().catch(() => 'Unknown');
          
          console.log(`  ❌ Laravel Error Found!`);
          console.log(`     Title: ${errorTitle}`);
          console.log(`     Message: ${errorMessage}`);
          
          // Try to get more details
          const sqlError = await page.locator('text=/SQLSTATE/').textContent().catch(() => null);
          if (sqlError) {
            console.log(`     SQL: ${sqlError}`);
          }
          
          // Take screenshot
          const screenshotPath = `tests/e2e/screenshots/errors/${route.path.replace(/\//g, '_')}_error_${Date.now()}.png`;
          await page.screenshot({ 
            path: screenshotPath,
            fullPage: true 
          });
          console.log(`     Screenshot: ${screenshotPath}`);
          
          errors.push({
            route: route.path,
            name: route.name,
            title: errorTitle,
            message: errorMessage,
            sql: sqlError,
            screenshot: screenshotPath
          });
          
          continue;
        }
        
        // Check for 500 error
        const has500 = await page.locator('text=/500|Internal Server Error/').isVisible().catch(() => false);
        if (has500) {
          console.log(`  ❌ 500 Internal Server Error`);
          
          // Try to get error details from the page
          const errorDetails = await page.locator('.error-message, .exception-message, pre').first().textContent().catch(() => 'No details available');
          console.log(`     Details: ${errorDetails.substring(0, 200)}...`);
          
          const screenshotPath = `tests/e2e/screenshots/errors/${route.path.replace(/\//g, '_')}_500_${Date.now()}.png`;
          await page.screenshot({ 
            path: screenshotPath,
            fullPage: true 
          });
          
          errors.push({
            route: route.path,
            name: route.name,
            type: '500 Error',
            details: errorDetails,
            screenshot: screenshotPath
          });
          
          continue;
        }
        
        // Check for database errors
        const hasDbError = await page.locator('text=/Column not found|Unknown column|SQLSTATE/').isVisible().catch(() => false);
        if (hasDbError) {
          const dbErrorText = await page.locator('text=/Column not found|Unknown column|SQLSTATE/').first().textContent().catch(() => 'Database error');
          console.log(`  ❌ Database Error: ${dbErrorText}`);
          
          const screenshotPath = `tests/e2e/screenshots/errors/${route.path.replace(/\//g, '_')}_db_${Date.now()}.png`;
          await page.screenshot({ 
            path: screenshotPath,
            fullPage: true 
          });
          
          errors.push({
            route: route.path,
            name: route.name,
            type: 'Database Error',
            message: dbErrorText,
            screenshot: screenshotPath
          });
          
          continue;
        }
        
        // Check for method/class errors
        const hasMethodError = await page.locator('text=/Call to undefined method|Class .* not found|Method .* does not exist/').isVisible().catch(() => false);
        if (hasMethodError) {
          const methodErrorText = await page.locator('text=/Call to undefined method|Class .* not found|Method .* does not exist/').first().textContent().catch(() => 'Method error');
          console.log(`  ❌ Method/Class Error: ${methodErrorText}`);
          
          const screenshotPath = `tests/e2e/screenshots/errors/${route.path.replace(/\//g, '_')}_method_${Date.now()}.png`;
          await page.screenshot({ 
            path: screenshotPath,
            fullPage: true 
          });
          
          errors.push({
            route: route.path,
            name: route.name,
            type: 'Method/Class Error',
            message: methodErrorText,
            screenshot: screenshotPath
          });
          
          continue;
        }
        
        // If no errors found
        console.log(`  ✅ No errors detected`);
        
      } catch (error) {
        console.log(`  ⚠️ Navigation error: ${error.message}`);
        errors.push({
          route: route.path,
          name: route.name,
          type: 'Navigation Error',
          message: error.message
        });
      }
    }
    
    // Summary
    console.log('\n' + '='.repeat(60));
    console.log('📊 Test Summary:');
    console.log(`Total Routes Tested: ${criticalRoutes.length}`);
    console.log(`Errors Found: ${errors.length}`);
    
    if (errors.length > 0) {
      console.log('\n🔴 Error Details:');
      errors.forEach((error, index) => {
        console.log(`\n${index + 1}. ${error.name} (${error.route})`);
        console.log(`   Type: ${error.type || error.title || 'Unknown'}`);
        console.log(`   Message: ${error.message || error.details || 'No message'}`);
        if (error.screenshot) {
          console.log(`   Screenshot: ${error.screenshot}`);
        }
      });
      
      // Save error report
      const reportContent = JSON.stringify(errors, null, 2);
      await page.evaluate((content) => {
        console.log('Error Report:', content);
      }, reportContent);
    }
    
    // Assert no critical errors
    expect(errors.length).toBe(0);
  });
});