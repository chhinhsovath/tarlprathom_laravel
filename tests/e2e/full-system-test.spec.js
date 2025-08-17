import { test, expect } from '@playwright/test';
import { 
  TEST_USERS, 
  login, 
  logout, 
  captureScreenshot, 
  checkPageAccess,
  checkNavigationMenu,
  testCRUDOperations,
  generateTestReport
} from './helpers/test-helpers.js';

const testResults = [];

test.describe('TaRL Assessment System - Full Role-Based Testing', () => {
  test.setTimeout(180000); // 3 minutes per test

  // Test Coordinator Role
  test('Coordinator Role - Full Access Test', async ({ page }) => {
    const startTime = Date.now();
    let testStatus = 'passed';
    let error = null;
    let screenshotPath = null;

    try {
      await login(page, 'coordinator');
      screenshotPath = await captureScreenshot(page, 'coordinator_dashboard', 'coordinator');

      // Check navigation menu
      await checkNavigationMenu(page, 'coordinator');

      // Test Dashboard Access
      await page.goto('/dashboard');
      await expect(page.locator('h1, h2').first()).toContainText(/Dashboard|Overview/i);
      await captureScreenshot(page, 'coordinator_dashboard_view', 'coordinator');

      // Test User Management
      await page.goto('/users');
      await expect(page).toHaveURL(/.*users/);
      await testCRUDOperations(page, 'User', 'coordinator');
      await captureScreenshot(page, 'coordinator_users', 'coordinator');

      // Test School Management
      await page.goto('/schools');
      await expect(page).toHaveURL(/.*schools/);
      await testCRUDOperations(page, 'School', 'coordinator');
      await captureScreenshot(page, 'coordinator_schools', 'coordinator');

      // Test Student Management
      await page.goto('/students');
      await expect(page).toHaveURL(/.*students/);
      await testCRUDOperations(page, 'Student', 'coordinator');
      await captureScreenshot(page, 'coordinator_students', 'coordinator');

      // Test Assessment Access
      await page.goto('/assessments');
      await expect(page).toHaveURL(/.*assessments/);
      await captureScreenshot(page, 'coordinator_assessments', 'coordinator');

      // Test Reports
      await page.goto('/reports');
      await expect(page).toHaveURL(/.*reports/);
      await captureScreenshot(page, 'coordinator_reports', 'coordinator');

      // Test Administration
      await page.goto('/administration');
      await expect(page).toHaveURL(/.*administration/);
      await captureScreenshot(page, 'coordinator_administration', 'coordinator');

    } catch (e) {
      testStatus = 'failed';
      error = e.message;
      screenshotPath = await captureScreenshot(page, 'coordinator_error', 'errors');
    }

    testResults.push({
      role: 'Coordinator',
      test: 'Full Access Test',
      status: testStatus,
      duration: `${(Date.now() - startTime) / 1000}s`,
      screenshot: screenshotPath,
      error
    });
  });

  // Test Admin Role
  test('Admin Role - Full Access Test', async ({ page }) => {
    const startTime = Date.now();
    let testStatus = 'passed';
    let error = null;
    let screenshotPath = null;

    try {
      await login(page, 'admin');
      screenshotPath = await captureScreenshot(page, 'admin_dashboard', 'admin');

      // Check navigation menu
      await checkNavigationMenu(page, 'admin');

      // Test Dashboard
      await page.goto('/dashboard');
      await expect(page.locator('h1, h2').first()).toContainText(/Dashboard|Overview/i);
      await captureScreenshot(page, 'admin_dashboard_view', 'admin');

      // Test User Management
      await page.goto('/users');
      await expect(page).toHaveURL(/.*users/);
      await testCRUDOperations(page, 'User', 'admin');
      await captureScreenshot(page, 'admin_users', 'admin');

      // Test School Management
      await page.goto('/schools');
      await expect(page).toHaveURL(/.*schools/);
      await testCRUDOperations(page, 'School', 'admin');
      await captureScreenshot(page, 'admin_schools', 'admin');

      // Test Resource Management (Admin only)
      await page.goto('/admin/resources');
      await expect(page).toHaveURL(/.*resources/);
      await captureScreenshot(page, 'admin_resources', 'admin');

      // Test Administration
      await page.goto('/administration');
      await expect(page).toHaveURL(/.*administration/);
      await captureScreenshot(page, 'admin_administration', 'admin');

      // Test Settings
      await page.goto('/settings');
      await captureScreenshot(page, 'admin_settings', 'admin');

    } catch (e) {
      testStatus = 'failed';
      error = e.message;
      screenshotPath = await captureScreenshot(page, 'admin_error', 'errors');
    }

    testResults.push({
      role: 'Admin',
      test: 'Full Access Test',
      status: testStatus,
      duration: `${(Date.now() - startTime) / 1000}s`,
      screenshot: screenshotPath,
      error
    });
  });

  // Test Mentor Role
  test('Mentor Role - Limited Access Test', async ({ page }) => {
    const startTime = Date.now();
    let testStatus = 'passed';
    let error = null;
    let screenshotPath = null;

    try {
      await login(page, 'mentor');
      screenshotPath = await captureScreenshot(page, 'mentor_dashboard', 'mentor');

      // Check navigation menu
      await checkNavigationMenu(page, 'mentor');

      // Test Dashboard
      await page.goto('/dashboard');
      await expect(page.locator('h1, h2').first()).toContainText(/Dashboard|Overview/i);
      await captureScreenshot(page, 'mentor_dashboard_view', 'mentor');

      // Test Student Access
      await page.goto('/students');
      await expect(page).toHaveURL(/.*students/);
      await captureScreenshot(page, 'mentor_students', 'mentor');

      // Test Mentoring Visits
      await page.goto('/mentoring');
      await expect(page).toHaveURL(/.*mentoring/);
      await testCRUDOperations(page, 'Visit', 'mentor');
      await captureScreenshot(page, 'mentor_mentoring', 'mentor');

      // Test Reports
      await page.goto('/reports');
      await expect(page).toHaveURL(/.*reports/);
      await captureScreenshot(page, 'mentor_reports', 'mentor');

      // Test Restricted Access - Should be denied
      await checkPageAccess(page, '/users', false);
      await captureScreenshot(page, 'mentor_restricted_users', 'mentor');

      await checkPageAccess(page, '/administration', false);
      await captureScreenshot(page, 'mentor_restricted_admin', 'mentor');

    } catch (e) {
      testStatus = 'failed';
      error = e.message;
      screenshotPath = await captureScreenshot(page, 'mentor_error', 'errors');
    }

    testResults.push({
      role: 'Mentor',
      test: 'Limited Access Test',
      status: testStatus,
      duration: `${(Date.now() - startTime) / 1000}s`,
      screenshot: screenshotPath,
      error
    });
  });

  // Test Teacher Role
  test('Teacher Role - Classroom Access Test', async ({ page }) => {
    const startTime = Date.now();
    let testStatus = 'passed';
    let error = null;
    let screenshotPath = null;

    try {
      await login(page, 'teacher');
      screenshotPath = await captureScreenshot(page, 'teacher_dashboard', 'teacher');

      // Check navigation menu
      await checkNavigationMenu(page, 'teacher');

      // Test Dashboard
      await page.goto('/dashboard');
      await expect(page.locator('h1, h2').first()).toContainText(/Dashboard|Overview/i);
      await captureScreenshot(page, 'teacher_dashboard_view', 'teacher');

      // Test Student Management
      await page.goto('/students');
      await expect(page).toHaveURL(/.*students/);
      await testCRUDOperations(page, 'Student', 'teacher');
      await captureScreenshot(page, 'teacher_students', 'teacher');

      // Test Class Management
      await page.goto('/classes');
      await expect(page).toHaveURL(/.*classes/);
      await captureScreenshot(page, 'teacher_classes', 'teacher');

      // Test Assessments
      await page.goto('/assessments');
      await expect(page).toHaveURL(/.*assessments/);
      await testCRUDOperations(page, 'Assessment', 'teacher');
      await captureScreenshot(page, 'teacher_assessments', 'teacher');

      // Test Teacher Reports
      await page.goto('/reports/my-students');
      await expect(page).toHaveURL(/.*reports\/my-students/);
      await captureScreenshot(page, 'teacher_my_students', 'teacher');

      await page.goto('/reports/class-progress');
      await expect(page).toHaveURL(/.*reports\/class-progress/);
      await captureScreenshot(page, 'teacher_class_progress', 'teacher');

      // Test Restricted Access
      await checkPageAccess(page, '/users', false);
      await captureScreenshot(page, 'teacher_restricted_users', 'teacher');

      await checkPageAccess(page, '/administration', false);
      await captureScreenshot(page, 'teacher_restricted_admin', 'teacher');

      await checkPageAccess(page, '/schools', false);
      await captureScreenshot(page, 'teacher_restricted_schools', 'teacher');

    } catch (e) {
      testStatus = 'failed';
      error = e.message;
      screenshotPath = await captureScreenshot(page, 'teacher_error', 'errors');
    }

    testResults.push({
      role: 'Teacher',
      test: 'Classroom Access Test',
      status: testStatus,
      duration: `${(Date.now() - startTime) / 1000}s`,
      screenshot: screenshotPath,
      error
    });
  });

  // Test Viewer Role
  test('Viewer Role - Read-Only Access Test', async ({ page }) => {
    const startTime = Date.now();
    let testStatus = 'passed';
    let error = null;
    let screenshotPath = null;

    try {
      await login(page, 'viewer');
      screenshotPath = await captureScreenshot(page, 'viewer_dashboard', 'viewer');

      // Check navigation menu
      await checkNavigationMenu(page, 'viewer');

      // Test Dashboard
      await page.goto('/dashboard');
      await expect(page.locator('h1, h2').first()).toContainText(/Dashboard|Overview/i);
      await captureScreenshot(page, 'viewer_dashboard_view', 'viewer');

      // Test Reports (Read-only)
      await page.goto('/reports');
      await expect(page).toHaveURL(/.*reports/);
      await captureScreenshot(page, 'viewer_reports', 'viewer');

      // Test Profile
      await page.goto('/profile');
      await expect(page).toHaveURL(/.*profile/);
      await captureScreenshot(page, 'viewer_profile', 'viewer');

      // Test Restricted Access - Should be denied
      await checkPageAccess(page, '/users', false);
      await captureScreenshot(page, 'viewer_restricted_users', 'viewer');

      await checkPageAccess(page, '/students/create', false);
      await captureScreenshot(page, 'viewer_restricted_create_student', 'viewer');

      await checkPageAccess(page, '/assessments/create', false);
      await captureScreenshot(page, 'viewer_restricted_create_assessment', 'viewer');

      await checkPageAccess(page, '/schools/create', false);
      await captureScreenshot(page, 'viewer_restricted_create_school', 'viewer');

      await checkPageAccess(page, '/administration', false);
      await captureScreenshot(page, 'viewer_restricted_admin', 'viewer');

    } catch (e) {
      testStatus = 'failed';
      error = e.message;
      screenshotPath = await captureScreenshot(page, 'viewer_error', 'errors');
    }

    testResults.push({
      role: 'Viewer',
      test: 'Read-Only Access Test',
      status: testStatus,
      duration: `${(Date.now() - startTime) / 1000}s`,
      screenshot: screenshotPath,
      error
    });
  });

  // Generate test report after all tests
  test.afterAll(async () => {
    const reportPath = await generateTestReport(testResults);
    console.log(`Test report generated: ${reportPath}`);
    console.log('\nTest Summary:');
    console.log(`Total: ${testResults.length}`);
    console.log(`Passed: ${testResults.filter(r => r.status === 'passed').length}`);
    console.log(`Failed: ${testResults.filter(r => r.status === 'failed').length}`);
  });
});