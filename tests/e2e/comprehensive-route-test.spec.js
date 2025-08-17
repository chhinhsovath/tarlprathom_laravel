import { test, expect } from '@playwright/test';
import { login } from './helpers/test-helpers';

// Define all routes to test
const routes = [
  // Dashboard & Home
  { path: '/dashboard', name: 'Dashboard' },
  { path: '/', name: 'Home' },
  
  // Schools
  { path: '/schools', name: 'Schools List' },
  { path: '/schools/create', name: 'Create School' },
  { path: '/schools/assessment-dates', name: 'Assessment Dates' },
  { path: '/schools/bulk-import', name: 'Bulk Import Schools' },
  
  // Students
  { path: '/students', name: 'Students List' },
  { path: '/students/create', name: 'Create Student' },
  { path: '/students/bulk-upload', name: 'Bulk Upload Students' },
  
  // Classes
  { path: '/classes', name: 'Classes List' },
  { path: '/classes/create', name: 'Create Class' },
  
  // Users
  { path: '/users', name: 'Users List' },
  { path: '/users/create', name: 'Create User' },
  
  // Assessments
  { path: '/assessments', name: 'Assessments List' },
  { path: '/assessments/create', name: 'Create Assessment' },
  { path: '/assessments/select-students', name: 'Select Students for Assessment' },
  { path: '/assessment-management', name: 'Assessment Management' },
  
  // Mentoring
  { path: '/mentoring', name: 'Mentoring Visits' },
  { path: '/mentoring/create', name: 'Create Mentoring Visit' },
  
  // Reports
  { path: '/reports', name: 'Reports Dashboard' },
  { path: '/reports/student-performance', name: 'Student Performance Report' },
  { path: '/reports/progress-tracking', name: 'Progress Tracking Report' },
  { path: '/reports/performance-calculation', name: 'Performance Calculation' },
  { path: '/reports/assessment-analysis', name: 'Assessment Analysis' },
  { path: '/reports/attendance', name: 'Attendance Report' },
  { path: '/reports/class-progress', name: 'Class Progress Report' },
  { path: '/reports/intervention', name: 'Intervention Report' },
  { path: '/reports/mentoring-impact', name: 'Mentoring Impact Report' },
  { path: '/reports/teacher-performance', name: 'Teacher Performance Report' },
  { path: '/reports/completion-status', name: 'Completion Status Report' },
  
  // Coordinator
  { path: '/coordinator/workspace', name: 'Coordinator Workspace' },
  { path: '/coordinator/system-overview', name: 'System Overview' },
  { path: '/coordinator/bulk-import', name: 'Coordinator Bulk Import' },
  { path: '/coordinator/language-management', name: 'Language Management' },
  
  // Settings & Help
  { path: '/settings', name: 'Settings' },
  { path: '/help', name: 'Help Center' },
  { path: '/profile', name: 'Profile' },
  
  // Administration
  { path: '/administration', name: 'Administration' },
  { path: '/localization', name: 'Localization' },
  
  // Import/Export
  { path: '/imports', name: 'Import Center' },
  { path: '/resources', name: 'Resources' },
  { path: '/showcase', name: 'Showcase' },
];

// Test users configuration
const testUsers = {
  coordinator: {
    email: 'coordinator@prathaminternational.org',
    password: 'admin123',
    role: 'Coordinator'
  },
  admin: {
    email: 'kairav@prathaminternational.org',
    password: 'admin123',
    role: 'Admin'
  },
  mentor: {
    email: 'mentor1@prathaminternational.org',
    password: 'admin123',
    role: 'Mentor'
  },
  teacher: {
    email: 'teacher1@prathaminternational.org',
    password: 'admin123',
    role: 'Teacher'
  },
  viewer: {
    email: 'viewer@prathaminternational.org',
    password: 'admin123',
    role: 'Viewer'
  }
};

// Expected access levels for each role
const accessMatrix = {
  coordinator: {
    allowed: ['/dashboard', '/', '/coordinator/workspace', '/coordinator/system-overview', 
              '/coordinator/bulk-import', '/coordinator/language-management', '/reports', 
              '/settings', '/help', '/profile', '/localization', '/showcase'],
    restricted: ['/schools', '/students', '/classes', '/users', '/assessments', 
                 '/mentoring', '/administration', '/imports']
  },
  admin: {
    allowed: ['all'], // Admin has access to everything
    restricted: []
  },
  mentor: {
    allowed: ['/dashboard', '/', '/mentoring', '/schools', '/students', '/assessments',
              '/reports', '/help', '/profile', '/showcase'],
    restricted: ['/users', '/administration', '/coordinator/workspace', '/settings',
                 '/localization', '/imports']
  },
  teacher: {
    allowed: ['/dashboard', '/', '/students', '/classes', '/assessments', '/reports',
              '/help', '/profile', '/showcase'],
    restricted: ['/schools', '/users', '/mentoring', '/administration', 
                 '/coordinator/workspace', '/settings', '/localization', '/imports']
  },
  viewer: {
    allowed: ['/dashboard', '/', '/reports', '/help', '/profile', '/showcase'],
    restricted: ['/schools/create', '/students/create', '/classes/create', '/users',
                 '/assessments/create', '/mentoring/create', '/administration',
                 '/coordinator/workspace', '/settings', '/localization', '/imports']
  }
};

test.describe('Comprehensive Route Testing for All User Roles', () => {
  test.setTimeout(300000); // 5 minutes timeout for comprehensive testing

  for (const [userKey, userInfo] of Object.entries(testUsers)) {
    test(`${userInfo.role} Role - Test All Routes`, async ({ page }) => {
      const testResults = [];
      const errors = [];
      
      console.log(`\n🧪 Testing ${userInfo.role} Role`);
      console.log('='.repeat(50));
      
      // Login using the userKey which corresponds to TEST_USERS keys
      await login(page, userKey);
      
      // Wait for successful login
      await page.waitForURL('**/dashboard', { timeout: 10000 }).catch(() => {
        console.log(`⚠️ Dashboard redirect failed for ${userInfo.role}`);
      });
      
      // Test each route
      for (const route of routes) {
        try {
          console.log(`Testing: ${route.path} - ${route.name}`);
          
          // Navigate to the route
          await page.goto(`http://localhost:8001${route.path}`, { 
            waitUntil: 'domcontentloaded',
            timeout: 10000 
          });
          
          // Wait a bit for the page to fully load
          await page.waitForTimeout(1000);
          
          // Check for various error indicators
          const hasError = await checkForErrors(page);
          
          if (hasError.error) {
            errors.push({
              role: userInfo.role,
              route: route.path,
              name: route.name,
              errorType: hasError.type,
              message: hasError.message
            });
            
            // Take screenshot of error
            const screenshotPath = `tests/e2e/screenshots/route-errors/${userInfo.role}_${route.path.replace(/\//g, '_')}_error.png`;
            await page.screenshot({ 
              path: screenshotPath,
              fullPage: true 
            });
            
            console.log(`  ❌ ERROR: ${hasError.type} - ${hasError.message}`);
            console.log(`     Screenshot: ${screenshotPath}`);
          } else {
            // Check if access is granted or denied
            const isAccessDenied = await page.locator('text=/403|Forbidden|Unauthorized|Access Denied/i').count() > 0;
            
            if (isAccessDenied) {
              console.log(`  🚫 Access Denied`);
              testResults.push({
                role: userInfo.role,
                route: route.path,
                name: route.name,
                status: 'denied'
              });
            } else {
              console.log(`  ✅ Success`);
              testResults.push({
                role: userInfo.role,
                route: route.path,
                name: route.name,
                status: 'success'
              });
              
              // Take screenshot of successful page
              const screenshotPath = `tests/e2e/screenshots/route-success/${userInfo.role}_${route.path.replace(/\//g, '_')}_success.png`;
              await page.screenshot({ 
                path: screenshotPath,
                fullPage: false 
              });
            }
          }
        } catch (error) {
          console.log(`  ⚠️ Navigation Error: ${error.message}`);
          errors.push({
            role: userInfo.role,
            route: route.path,
            name: route.name,
            errorType: 'Navigation',
            message: error.message
          });
        }
      }
      
      // Generate summary report
      console.log(`\n📊 Summary for ${userInfo.role} Role:`);
      console.log(`Total Routes Tested: ${routes.length}`);
      console.log(`Successful: ${testResults.filter(r => r.status === 'success').length}`);
      console.log(`Access Denied: ${testResults.filter(r => r.status === 'denied').length}`);
      console.log(`Errors: ${errors.length}`);
      
      if (errors.length > 0) {
        console.log('\n🔴 Errors Found:');
        errors.forEach(error => {
          console.log(`  - ${error.route} (${error.name}): ${error.errorType} - ${error.message}`);
        });
      }
      
      // Save detailed report
      const reportPath = `tests/e2e/reports/${userInfo.role}_route_test_report.json`;
      await page.evaluate((data) => {
        const fs = require('fs');
        fs.writeFileSync(data.path, JSON.stringify(data.content, null, 2));
      }, {
        path: reportPath,
        content: {
          role: userInfo.role,
          timestamp: new Date().toISOString(),
          summary: {
            total: routes.length,
            successful: testResults.filter(r => r.status === 'success').length,
            denied: testResults.filter(r => r.status === 'denied').length,
            errors: errors.length
          },
          results: testResults,
          errors: errors
        }
      }).catch(() => {
        // If file writing fails in browser context, log to console
        console.log('\nDetailed Report:', JSON.stringify({
          role: userInfo.role,
          results: testResults,
          errors: errors
        }, null, 2));
      });
      
      // Assert no critical errors
      expect(errors.filter(e => 
        e.errorType === 'Laravel Error' || 
        e.errorType === 'Database Error' ||
        e.errorType === 'PHP Fatal Error'
      ).length).toBe(0);
    });
  }
});

// Helper function to check for various types of errors
async function checkForErrors(page) {
  // Check for Laravel error page
  const laravelError = await page.locator('.exception-message, .exception_message, text=/Exception|Error:/i').first().isVisible().catch(() => false);
  if (laravelError) {
    const message = await page.locator('.exception-message, .exception_message').first().textContent().catch(() => 'Unknown error');
    return { error: true, type: 'Laravel Error', message };
  }
  
  // Check for PHP errors
  const phpError = await page.locator('text=/Fatal error|Parse error|Warning:|Notice:/i').first().isVisible().catch(() => false);
  if (phpError) {
    const message = await page.locator('text=/Fatal error|Parse error|Warning:|Notice:/i').first().textContent().catch(() => 'PHP Error');
    return { error: true, type: 'PHP Error', message };
  }
  
  // Check for database errors
  const dbError = await page.locator('text=/SQLSTATE|Database Error|QueryException/i').first().isVisible().catch(() => false);
  if (dbError) {
    const message = await page.locator('text=/SQLSTATE|Database Error|QueryException/i').first().textContent().catch(() => 'Database Error');
    return { error: true, type: 'Database Error', message };
  }
  
  // Check for 500 errors
  const error500 = await page.locator('text=/500|Internal Server Error/i').first().isVisible().catch(() => false);
  if (error500) {
    return { error: true, type: '500 Error', message: 'Internal Server Error' };
  }
  
  // Check for 404 errors
  const error404 = await page.locator('text=/404|Not Found|Page Not Found/i').first().isVisible().catch(() => false);
  if (error404) {
    return { error: true, type: '404 Error', message: 'Page Not Found' };
  }
  
  // Check for missing method errors
  const methodError = await page.locator('text=/Method .* does not exist|Call to undefined method/i').first().isVisible().catch(() => false);
  if (methodError) {
    const message = await page.locator('text=/Method .* does not exist|Call to undefined method/i').first().textContent().catch(() => 'Method Error');
    return { error: true, type: 'Method Error', message };
  }
  
  // Check for missing column errors
  const columnError = await page.locator('text=/Unknown column|Column not found/i').first().isVisible().catch(() => false);
  if (columnError) {
    const message = await page.locator('text=/Unknown column|Column not found/i').first().textContent().catch(() => 'Column Error');
    return { error: true, type: 'Database Column Error', message };
  }
  
  return { error: false };
}