import { expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';

export const TEST_USERS = {
  coordinator: {
    email: 'coordinator@prathaminternational.org',
    password: 'admin123',
    role: 'coordinator',
    expectedPages: [
      '/dashboard',
      '/students',
      '/assessments',
      '/mentoring',
      '/reports',
      '/schools',
      '/users',
      '/administration',
      '/settings',
      '/help'
    ]
  },
  admin: {
    email: 'kairav@prathaminternational.org',
    password: 'admin123',
    role: 'admin',
    expectedPages: [
      '/dashboard',
      '/students',
      '/assessments',
      '/mentoring',
      '/reports',
      '/schools',
      '/users',
      '/administration',
      '/admin/resources',
      '/settings',
      '/help'
    ]
  },
  mentor: {
    email: 'mentor1@prathaminternational.org',
    password: 'admin123',
    role: 'mentor',
    expectedPages: [
      '/dashboard',
      '/students',
      '/assessments',
      '/mentoring',
      '/reports',
      '/schools',
      '/profile'
    ],
    restrictedPages: [
      '/users',
      '/administration',
      '/admin/resources'
    ]
  },
  teacher: {
    email: 'teacher1@prathaminternational.org',
    password: 'admin123',
    role: 'teacher',
    expectedPages: [
      '/dashboard',
      '/students',
      '/assessments',
      '/classes',
      '/reports/my-students',
      '/reports/class-progress',
      '/profile'
    ],
    restrictedPages: [
      '/users',
      '/administration',
      '/mentoring',
      '/schools'
    ]
  },
  viewer: {
    email: 'viewer@prathaminternational.org',
    password: 'admin123',
    role: 'viewer',
    expectedPages: [
      '/dashboard',
      '/reports',
      '/profile'
    ],
    restrictedPages: [
      '/users',
      '/administration',
      '/students/create',
      '/assessments/create',
      '/mentoring/create',
      '/schools/create'
    ]
  }
};

export async function login(page, userType) {
  const user = TEST_USERS[userType];
  
  await page.goto('/login');
  await page.fill('input[name="email"]', user.email);
  await page.fill('input[name="password"]', user.password);
  await page.click('button[type="submit"]');
  
  // Wait for successful login
  await page.waitForURL('**/dashboard', { timeout: 10000 });
  
  // Verify we're logged in
  await expect(page.locator('body')).toContainText(user.email.split('@')[0], { ignoreCase: true });
}

export async function logout(page) {
  // Click on user dropdown
  await page.click('button[id="user-menu-button"]').catch(() => {
    // Try alternative logout methods
    return page.click('[data-dropdown-toggle="dropdown-user"]');
  });
  
  // Click logout
  await page.click('text=Log Out');
  
  // Wait for redirect to login page
  await page.waitForURL('**/login', { timeout: 5000 });
}

export async function captureScreenshot(page, name, folder = 'general') {
  const screenshotDir = path.join('tests', 'e2e', 'screenshots', folder);
  
  // Create directory if it doesn't exist
  if (!fs.existsSync(screenshotDir)) {
    fs.mkdirSync(screenshotDir, { recursive: true });
  }
  
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
  const filename = `${name}_${timestamp}.png`;
  const filepath = path.join(screenshotDir, filename);
  
  await page.screenshot({ 
    path: filepath, 
    fullPage: true 
  });
  
  return filepath;
}

export async function checkPageAccess(page, url, shouldHaveAccess = true) {
  await page.goto(url);
  
  if (shouldHaveAccess) {
    // Should not see 403 or unauthorized message
    await expect(page.locator('body')).not.toContainText('403');
    await expect(page.locator('body')).not.toContainText('Access denied');
    await expect(page.locator('body')).not.toContainText('Unauthorized');
    
    // Should see some expected content (not login page)
    await expect(page.url()).not.toContain('/login');
  } else {
    // Should see 403 or be redirected
    const bodyText = await page.locator('body').textContent();
    const isBlocked = 
      bodyText.includes('403') || 
      bodyText.includes('Access denied') || 
      bodyText.includes('Unauthorized') ||
      page.url().includes('/login');
    
    expect(isBlocked).toBeTruthy();
  }
}

export async function checkNavigationMenu(page, userType) {
  const user = TEST_USERS[userType];
  
  // Check if expected menu items are visible
  for (const menuPath of user.expectedPages) {
    const menuText = menuPath.split('/').pop() || 'dashboard';
    const formattedText = menuText.charAt(0).toUpperCase() + menuText.slice(1);
    
    // Some menu items might be in dropdowns
    const menuItem = page.locator(`nav a[href="${menuPath}"], nav a:has-text("${formattedText}")`).first();
    
    if (await menuItem.isVisible()) {
      await expect(menuItem).toBeVisible();
    }
  }
  
  // Check that restricted items are not visible
  if (user.restrictedPages) {
    for (const restrictedPath of user.restrictedPages) {
      const menuItem = page.locator(`nav a[href="${restrictedPath}"]`).first();
      
      if (await menuItem.count() > 0) {
        await expect(menuItem).not.toBeVisible();
      }
    }
  }
}

export async function testCRUDOperations(page, resourceType, userRole) {
  const canCreate = !['viewer'].includes(userRole);
  const canEdit = !['viewer'].includes(userRole);
  const canDelete = ['admin', 'coordinator'].includes(userRole);
  
  // Test Create
  if (canCreate) {
    const createButton = page.locator(`a:has-text("Add ${resourceType}"), button:has-text("Add ${resourceType}")`).first();
    if (await createButton.isVisible()) {
      await expect(createButton).toBeEnabled();
    }
  }
  
  // Test Edit (if items exist)
  const editButtons = page.locator('a:has-text("Edit"), button:has-text("Edit")');
  if (await editButtons.count() > 0 && canEdit) {
    await expect(editButtons.first()).toBeEnabled();
  }
  
  // Test Delete (if items exist)
  const deleteButtons = page.locator('button:has-text("Delete")');
  if (await deleteButtons.count() > 0) {
    if (canDelete) {
      await expect(deleteButtons.first()).toBeEnabled();
    } else {
      // Delete buttons should not be visible for non-admin users
      await expect(deleteButtons.first()).not.toBeVisible();
    }
  }
}

export async function generateTestReport(results) {
  const reportDir = path.join('tests', 'e2e', 'reports');
  
  if (!fs.existsSync(reportDir)) {
    fs.mkdirSync(reportDir, { recursive: true });
  }
  
  const timestamp = new Date().toISOString();
  const report = {
    timestamp,
    results,
    summary: {
      total: results.length,
      passed: results.filter(r => r.status === 'passed').length,
      failed: results.filter(r => r.status === 'failed').length,
      skipped: results.filter(r => r.status === 'skipped').length
    }
  };
  
  // Write JSON report
  fs.writeFileSync(
    path.join(reportDir, 'test-report.json'),
    JSON.stringify(report, null, 2)
  );
  
  // Write HTML report
  const htmlReport = `
<!DOCTYPE html>
<html>
<head>
  <title>TaRL Assessment System - Test Report</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1 { color: #333; }
    .summary { 
      background: #f0f0f0; 
      padding: 15px; 
      border-radius: 5px; 
      margin: 20px 0;
    }
    .passed { color: green; }
    .failed { color: red; }
    .skipped { color: orange; }
    table { 
      width: 100%; 
      border-collapse: collapse; 
      margin-top: 20px;
    }
    th, td { 
      border: 1px solid #ddd; 
      padding: 12px; 
      text-align: left;
    }
    th { background: #f5f5f5; }
    tr:nth-child(even) { background: #f9f9f9; }
    .screenshot-link { color: blue; text-decoration: underline; cursor: pointer; }
  </style>
</head>
<body>
  <h1>TaRL Assessment System - Automated Test Report</h1>
  <p>Generated: ${timestamp}</p>
  
  <div class="summary">
    <h2>Test Summary</h2>
    <p>Total Tests: ${report.summary.total}</p>
    <p class="passed">Passed: ${report.summary.passed}</p>
    <p class="failed">Failed: ${report.summary.failed}</p>
    <p class="skipped">Skipped: ${report.summary.skipped}</p>
  </div>
  
  <h2>Test Results by Role</h2>
  <table>
    <thead>
      <tr>
        <th>Role</th>
        <th>Test</th>
        <th>Status</th>
        <th>Duration</th>
        <th>Screenshot</th>
        <th>Error</th>
      </tr>
    </thead>
    <tbody>
      ${results.map(r => `
        <tr>
          <td>${r.role}</td>
          <td>${r.test}</td>
          <td class="${r.status}">${r.status}</td>
          <td>${r.duration || 'N/A'}</td>
          <td>${r.screenshot ? `<a href="${r.screenshot}" class="screenshot-link">View</a>` : 'N/A'}</td>
          <td>${r.error || ''}</td>
        </tr>
      `).join('')}
    </tbody>
  </table>
</body>
</html>
  `;
  
  fs.writeFileSync(
    path.join(reportDir, 'test-report.html'),
    htmlReport
  );
  
  return path.join(reportDir, 'test-report.html');
}