import { test, expect } from '@playwright/test';
import { TEST_USERS, login, logout, captureScreenshot } from './helpers/test-helpers.js';
import fs from 'fs';
import path from 'path';

// Comprehensive list of all application routes by role
const ALL_ROUTES = {
  admin: [
    // Dashboard
    { path: '/dashboard', name: 'Dashboard', requiresData: false },
    
    // Students
    { path: '/students', name: 'Students List', requiresData: false },
    { path: '/students/create', name: 'Create Student', requiresData: false },
    { path: '/students/bulk-import', name: 'Bulk Import Students', requiresData: false },
    
    // Assessments
    { path: '/assessments', name: 'Assessments List', requiresData: false },
    { path: '/assessments/create', name: 'Create Assessment', requiresData: false },
    { path: '/assessments/select-students', name: 'Select Students for Assessment', requiresData: false },
    
    // Mentoring
    { path: '/mentoring', name: 'Mentoring Visits', requiresData: false },
    { path: '/mentoring/create', name: 'Create Mentoring Visit', requiresData: false },
    
    // Reports
    { path: '/reports', name: 'Reports Dashboard', requiresData: false },
    { path: '/reports/student-performance', name: 'Student Performance Report', requiresData: false },
    { path: '/reports/progress-tracking', name: 'Progress Tracking Report', requiresData: false },
    { path: '/reports/school-comparison', name: 'School Comparison Report', requiresData: false },
    { path: '/reports/performance-calculation', name: 'Performance Calculation', requiresData: false },
    { path: '/reports/my-students', name: 'My Students Report', requiresData: false },
    { path: '/reports/class-progress', name: 'Class Progress Report', requiresData: false },
    { path: '/reports/mentoring-impact', name: 'Mentoring Impact Report', requiresData: false },
    { path: '/reports/school-visits', name: 'School Visits Report', requiresData: false },
    { path: '/reports/assessment-analysis', name: 'Assessment Analysis', requiresData: false },
    { path: '/reports/attendance-report', name: 'Attendance Report', requiresData: false },
    { path: '/reports/intervention-report', name: 'Intervention Report', requiresData: false },
    { path: '/reports/student-progress', name: 'Student Progress Report', requiresData: false },
    
    // Schools
    { path: '/schools', name: 'Schools List', requiresData: false },
    { path: '/schools/create', name: 'Create School', requiresData: false },
    { path: '/schools/assessment-dates', name: 'Assessment Dates', requiresData: false },
    { path: '/schools/bulk-import', name: 'Bulk Import Schools', requiresData: false },
    
    // Classes
    { path: '/classes', name: 'Classes List', requiresData: false },
    { path: '/classes/create', name: 'Create Class', requiresData: false },
    
    // Users
    { path: '/users', name: 'Users List', requiresData: false },
    { path: '/users/create', name: 'Create User', requiresData: false },
    { path: '/users/bulk-import', name: 'Bulk Import Users', requiresData: false },
    { path: '/users/bulk-import-enhanced', name: 'Enhanced Bulk Import Users', requiresData: false },
    
    // Administration
    { path: '/administration', name: 'Administration', requiresData: false },
    { path: '/assessment-management', name: 'Assessment Management', requiresData: false },
    { path: '/assessment-management/mentoring-visits', name: 'Mentoring Visits Management', requiresData: false },
    { path: '/admin/resources', name: 'Resources Management', requiresData: false },
    { path: '/admin/resources/create', name: 'Create Resource', requiresData: false },
    
    // Coordinator features
    { path: '/coordinator', name: 'Coordinator Workspace', requiresData: false },
    { path: '/coordinator/system-overview', name: 'System Overview', requiresData: false },
    { path: '/coordinator/bulk-import', name: 'Coordinator Bulk Import', requiresData: false },
    { path: '/coordinator/language-management', name: 'Language Management', requiresData: false },
    
    // Settings
    { path: '/settings', name: 'Settings', requiresData: false },
    { path: '/localization', name: 'Localization Settings', requiresData: false },
    { path: '/localization/edit', name: 'Edit Translations', requiresData: false },
    
    // Help & Profile
    { path: '/help', name: 'Help', requiresData: false },
    { path: '/about', name: 'About', requiresData: false },
    { path: '/profile', name: 'Profile', requiresData: false }
  ],
  
  coordinator: [
    { path: '/dashboard', name: 'Dashboard', requiresData: false },
    { path: '/students', name: 'Students List', requiresData: false },
    { path: '/assessments', name: 'Assessments List', requiresData: false },
    { path: '/mentoring', name: 'Mentoring Visits', requiresData: false },
    { path: '/reports', name: 'Reports Dashboard', requiresData: false },
    { path: '/schools', name: 'Schools List', requiresData: false },
    { path: '/users', name: 'Users List', requiresData: false },
    { path: '/administration', name: 'Administration', requiresData: false },
    { path: '/coordinator', name: 'Coordinator Workspace', requiresData: false },
    { path: '/coordinator/system-overview', name: 'System Overview', requiresData: false },
    { path: '/coordinator/bulk-import', name: 'Bulk Import', requiresData: false },
    { path: '/coordinator/language-management', name: 'Language Management', requiresData: false },
    { path: '/settings', name: 'Settings', requiresData: false },
    { path: '/help', name: 'Help', requiresData: false },
    { path: '/profile', name: 'Profile', requiresData: false }
  ],
  
  mentor: [
    { path: '/dashboard', name: 'Dashboard', requiresData: false },
    { path: '/students', name: 'Students List', requiresData: false },
    { path: '/students/create', name: 'Create Student', requiresData: false },
    { path: '/assessments', name: 'Assessments List', requiresData: false },
    { path: '/assessments/create', name: 'Create Assessment', requiresData: false },
    { path: '/assessments/select-students', name: 'Select Students', requiresData: false },
    { path: '/mentoring', name: 'Mentoring Visits', requiresData: false },
    { path: '/mentoring/create', name: 'Create Mentoring Visit', requiresData: false },
    { path: '/reports', name: 'Reports Dashboard', requiresData: false },
    { path: '/reports/student-performance', name: 'Student Performance', requiresData: false },
    { path: '/reports/progress-tracking', name: 'Progress Tracking', requiresData: false },
    { path: '/reports/my-mentoring', name: 'My Mentoring', requiresData: false },
    { path: '/schools', name: 'Schools List', requiresData: false },
    { path: '/profile', name: 'Profile', requiresData: false }
  ],
  
  teacher: [
    { path: '/dashboard', name: 'Dashboard', requiresData: false },
    { path: '/students', name: 'Students List', requiresData: false },
    { path: '/students/create', name: 'Create Student', requiresData: false },
    { path: '/assessments', name: 'Assessments List', requiresData: false },
    { path: '/assessments/create', name: 'Create Assessment', requiresData: false },
    { path: '/classes', name: 'Classes List', requiresData: false },
    { path: '/classes/create', name: 'Create Class', requiresData: false },
    { path: '/reports/my-students', name: 'My Students', requiresData: false },
    { path: '/reports/class-progress', name: 'Class Progress', requiresData: false },
    { path: '/profile', name: 'Profile', requiresData: false }
  ],
  
  viewer: [
    { path: '/dashboard', name: 'Dashboard', requiresData: false },
    { path: '/reports', name: 'Reports Dashboard', requiresData: false },
    { path: '/reports/student-performance', name: 'Student Performance', requiresData: false },
    { path: '/reports/progress-tracking', name: 'Progress Tracking', requiresData: false },
    { path: '/profile', name: 'Profile', requiresData: false }
  ]
};

// Error patterns to check for
const ERROR_PATTERNS = [
  { pattern: /500\s*(Internal Server Error)?/i, type: '500 Error' },
  { pattern: /404\s*(Not Found)?/i, type: '404 Error' },
  { pattern: /403\s*(Forbidden)?/i, type: '403 Error' },
  { pattern: /401\s*(Unauthorized)?/i, type: '401 Error' },
  { pattern: /419\s*(Page Expired)?/i, type: '419 Error' },
  { pattern: /Undefined variable/i, type: 'PHP Undefined Variable' },
  { pattern: /Undefined index/i, type: 'PHP Undefined Index' },
  { pattern: /Undefined array key/i, type: 'PHP Undefined Array Key' },
  { pattern: /Call to undefined/i, type: 'PHP Undefined Function' },
  { pattern: /Class .* not found/i, type: 'PHP Class Not Found' },
  { pattern: /Trying to get property .* of non-object/i, type: 'PHP Property of Non-Object' },
  { pattern: /SQLSTATE/i, type: 'Database Error' },
  { pattern: /QueryException/i, type: 'Database Query Exception' },
  { pattern: /Deadlock found/i, type: 'Database Deadlock' },
  { pattern: /Connection refused/i, type: 'Connection Error' },
  { pattern: /Maximum execution time/i, type: 'Timeout Error' },
  { pattern: /Allowed memory size .* exhausted/i, type: 'Memory Error' },
  { pattern: /ParseError/i, type: 'PHP Parse Error' },
  { pattern: /ErrorException/i, type: 'Laravel Error Exception' },
  { pattern: /MethodNotAllowedHttpException/i, type: 'Method Not Allowed' },
  { pattern: /TokenMismatchException/i, type: 'CSRF Token Error' },
  { pattern: /ModelNotFoundException/i, type: 'Model Not Found' },
  { pattern: /RouteNotFoundException/i, type: 'Route Not Found' },
  { pattern: /ViewException/i, type: 'View Error' },
  { pattern: /Whoops/i, type: 'Laravel Debug Page' }
];

// JavaScript console error patterns
const JS_ERROR_PATTERNS = [
  'SyntaxError',
  'ReferenceError',
  'TypeError',
  'RangeError',
  'EvalError',
  'URIError',
  'Cannot read property',
  'Cannot read properties',
  'is not defined',
  'is not a function',
  'Unexpected token',
  'Uncaught'
];

async function checkPageForErrors(page, pageInfo) {
  const errors = [];
  
  try {
    // Collect console errors
    const consoleErrors = [];
    page.on('console', msg => {
      if (msg.type() === 'error') {
        const text = msg.text();
        // Ignore common non-critical console errors
        if (!text.includes('favicon.ico') && 
            !text.includes('Failed to load resource') &&
            !text.includes('net::ERR_')) {
          consoleErrors.push(text);
        }
      }
    });
    
    // Navigate to the page
    const response = await page.goto(pageInfo.path, { 
      waitUntil: 'networkidle',
      timeout: 30000 
    });
    
    // Check HTTP status
    const status = response.status();
    if (status >= 400) {
      errors.push({
        type: 'HTTP Error',
        message: `HTTP ${status} response`,
        severity: 'high'
      });
    }
    
    // Wait for page to fully load
    await page.waitForTimeout(2000);
    
    // Check for PHP/Laravel errors in page content
    const pageContent = await page.content();
    const bodyText = await page.locator('body').innerText().catch(() => '');
    
    for (const errorPattern of ERROR_PATTERNS) {
      if (errorPattern.pattern.test(pageContent) || errorPattern.pattern.test(bodyText)) {
        const match = pageContent.match(errorPattern.pattern) || bodyText.match(errorPattern.pattern);
        errors.push({
          type: errorPattern.type,
          message: match[0].substring(0, 200),
          severity: 'high'
        });
      }
    }
    
    // Check for JavaScript errors
    for (const jsError of consoleErrors) {
      for (const pattern of JS_ERROR_PATTERNS) {
        if (jsError.includes(pattern)) {
          errors.push({
            type: 'JavaScript Error',
            message: jsError.substring(0, 200),
            severity: 'medium'
          });
          break;
        }
      }
    }
    
    // Check if page has minimum expected content
    if (bodyText.length < 50 && !errors.length) {
      errors.push({
        type: 'Empty Page',
        message: 'Page appears to be empty or has minimal content',
        severity: 'medium'
      });
    }
    
    // Check for broken images
    const brokenImages = await page.evaluate(() => {
      const images = Array.from(document.querySelectorAll('img'));
      return images.filter(img => !img.complete || img.naturalWidth === 0)
        .map(img => img.src);
    });
    
    if (brokenImages.length > 0) {
      errors.push({
        type: 'Broken Images',
        message: `Found ${brokenImages.length} broken image(s)`,
        severity: 'low',
        details: brokenImages
      });
    }
    
    // Check for form validation
    const forms = await page.locator('form').count();
    if (forms > 0) {
      // Check if forms have CSRF tokens
      const csrfTokens = await page.locator('input[name="_token"], meta[name="csrf-token"]').count();
      if (csrfTokens === 0) {
        errors.push({
          type: 'Missing CSRF Token',
          message: 'Form without CSRF protection detected',
          severity: 'high'
        });
      }
    }
    
    // Check for accessibility issues (basic checks)
    const accessibilityIssues = await page.evaluate(() => {
      const issues = [];
      
      // Check for images without alt text
      const imagesWithoutAlt = document.querySelectorAll('img:not([alt])');
      if (imagesWithoutAlt.length > 0) {
        issues.push(`${imagesWithoutAlt.length} images without alt text`);
      }
      
      // Check for form inputs without labels
      const inputsWithoutLabels = document.querySelectorAll('input:not([type="hidden"]):not([type="submit"])');
      let unlabeledInputs = 0;
      inputsWithoutLabels.forEach(input => {
        if (!input.labels || input.labels.length === 0) {
          if (!input.getAttribute('aria-label') && !input.getAttribute('placeholder')) {
            unlabeledInputs++;
          }
        }
      });
      if (unlabeledInputs > 0) {
        issues.push(`${unlabeledInputs} form inputs without labels`);
      }
      
      return issues;
    });
    
    if (accessibilityIssues.length > 0) {
      errors.push({
        type: 'Accessibility Issues',
        message: accessibilityIssues.join(', '),
        severity: 'low'
      });
    }
    
  } catch (error) {
    errors.push({
      type: 'Page Load Error',
      message: error.message,
      severity: 'high'
    });
  }
  
  return errors;
}

// Generate detailed HTML report
async function generateDetailedReport(allResults) {
  const timestamp = new Date().toISOString();
  const reportDir = path.join('tests', 'e2e', 'reports');
  
  if (!fs.existsSync(reportDir)) {
    fs.mkdirSync(reportDir, { recursive: true });
  }
  
  // Calculate statistics
  const stats = {
    totalPages: 0,
    totalErrors: 0,
    errorsByType: {},
    errorsBySeverity: { high: 0, medium: 0, low: 0 },
    roleStats: {}
  };
  
  allResults.forEach(roleResult => {
    stats.roleStats[roleResult.role] = {
      total: roleResult.pages.length,
      passed: roleResult.pages.filter(p => p.errors.length === 0).length,
      failed: roleResult.pages.filter(p => p.errors.length > 0).length
    };
    
    roleResult.pages.forEach(page => {
      stats.totalPages++;
      page.errors.forEach(error => {
        stats.totalErrors++;
        stats.errorsByType[error.type] = (stats.errorsByType[error.type] || 0) + 1;
        stats.errorsBySeverity[error.severity] = (stats.errorsBySeverity[error.severity] || 0) + 1;
      });
    });
  });
  
  // Generate HTML report
  const htmlReport = `
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comprehensive Page Inspection Report - ${timestamp}</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { 
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
      line-height: 1.6;
      color: #333;
      background: #f5f5f5;
    }
    .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
    header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px;
      border-radius: 10px;
      margin-bottom: 30px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    h1 { font-size: 2.5em; margin-bottom: 10px; }
    .timestamp { opacity: 0.9; font-size: 0.9em; }
    
    .summary-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }
    
    .stat-card {
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .stat-card:hover { transform: translateY(-2px); }
    .stat-value { font-size: 2em; font-weight: bold; margin: 10px 0; }
    .stat-label { color: #666; font-size: 0.9em; text-transform: uppercase; }
    
    .severity-high { color: #e53e3e; }
    .severity-medium { color: #dd6b20; }
    .severity-low { color: #d69e2e; }
    .success { color: #38a169; }
    
    .role-section {
      background: white;
      border-radius: 10px;
      padding: 25px;
      margin-bottom: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .role-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 2px solid #e2e8f0;
    }
    
    .role-title { font-size: 1.5em; color: #2d3748; }
    .role-stats { display: flex; gap: 20px; }
    .role-stat { text-align: center; }
    
    .pages-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }
    
    .pages-table th {
      background: #f7fafc;
      padding: 12px;
      text-align: left;
      font-weight: 600;
      color: #4a5568;
      border-bottom: 2px solid #e2e8f0;
    }
    
    .pages-table td {
      padding: 12px;
      border-bottom: 1px solid #e2e8f0;
    }
    
    .pages-table tr:hover { background: #f7fafc; }
    
    .page-status {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85em;
      font-weight: 600;
    }
    .status-pass { background: #c6f6d5; color: #22543d; }
    .status-fail { background: #fed7d7; color: #742a2a; }
    
    .error-list {
      list-style: none;
      margin: 5px 0;
    }
    
    .error-item {
      padding: 8px;
      margin: 5px 0;
      border-left: 3px solid;
      background: #f7fafc;
      border-radius: 3px;
      font-size: 0.9em;
    }
    
    .error-high { border-color: #e53e3e; }
    .error-medium { border-color: #dd6b20; }
    .error-low { border-color: #d69e2e; }
    
    .error-type { font-weight: 600; margin-right: 10px; }
    .error-message { color: #4a5568; }
    
    .chart-container {
      background: white;
      padding: 25px;
      border-radius: 10px;
      margin-bottom: 25px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .footer {
      text-align: center;
      padding: 20px;
      color: #718096;
      font-size: 0.9em;
    }
    
    @media (max-width: 768px) {
      .summary-grid { grid-template-columns: 1fr; }
      .role-stats { flex-direction: column; gap: 10px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <header>
      <h1>🔍 Comprehensive Page Inspection Report</h1>
      <div class="timestamp">Generated: ${timestamp}</div>
    </header>
    
    <div class="summary-grid">
      <div class="stat-card">
        <div class="stat-label">Total Pages Tested</div>
        <div class="stat-value">${stats.totalPages}</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-label">Total Errors Found</div>
        <div class="stat-value ${stats.totalErrors > 0 ? 'severity-high' : 'success'}">
          ${stats.totalErrors}
        </div>
      </div>
      
      <div class="stat-card">
        <div class="stat-label">High Severity</div>
        <div class="stat-value severity-high">${stats.errorsBySeverity.high}</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-label">Medium Severity</div>
        <div class="stat-value severity-medium">${stats.errorsBySeverity.medium}</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-label">Low Severity</div>
        <div class="stat-value severity-low">${stats.errorsBySeverity.low}</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-label">Success Rate</div>
        <div class="stat-value success">
          ${((stats.totalPages - allResults.reduce((acc, r) => 
            acc + r.pages.filter(p => p.errors.length > 0).length, 0)) / stats.totalPages * 100).toFixed(1)}%
        </div>
      </div>
    </div>
    
    ${allResults.map(roleResult => `
      <div class="role-section">
        <div class="role-header">
          <h2 class="role-title">👤 ${roleResult.role.toUpperCase()} Role</h2>
          <div class="role-stats">
            <div class="role-stat">
              <div class="stat-value success">${stats.roleStats[roleResult.role].passed}</div>
              <div class="stat-label">Passed</div>
            </div>
            <div class="role-stat">
              <div class="stat-value severity-high">${stats.roleStats[roleResult.role].failed}</div>
              <div class="stat-label">Failed</div>
            </div>
            <div class="role-stat">
              <div class="stat-value">${stats.roleStats[roleResult.role].total}</div>
              <div class="stat-label">Total</div>
            </div>
          </div>
        </div>
        
        <table class="pages-table">
          <thead>
            <tr>
              <th style="width: 30%">Page</th>
              <th style="width: 15%">Status</th>
              <th style="width: 55%">Issues</th>
            </tr>
          </thead>
          <tbody>
            ${roleResult.pages.map(page => `
              <tr>
                <td>
                  <strong>${page.name}</strong><br>
                  <code style="font-size: 0.85em; color: #718096;">${page.path}</code>
                </td>
                <td>
                  <span class="page-status ${page.errors.length === 0 ? 'status-pass' : 'status-fail'}">
                    ${page.errors.length === 0 ? '✓ PASS' : `✗ ${page.errors.length} ISSUE${page.errors.length > 1 ? 'S' : ''}`}
                  </span>
                </td>
                <td>
                  ${page.errors.length > 0 ? `
                    <ul class="error-list">
                      ${page.errors.map(error => `
                        <li class="error-item error-${error.severity}">
                          <span class="error-type">${error.type}:</span>
                          <span class="error-message">${error.message}</span>
                        </li>
                      `).join('')}
                    </ul>
                  ` : '<span style="color: #38a169;">✓ No issues detected</span>'}
                </td>
              </tr>
            `).join('')}
          </tbody>
        </table>
      </div>
    `).join('')}
    
    <div class="chart-container">
      <h2 style="margin-bottom: 20px;">Error Distribution by Type</h2>
      <div style="display: flex; flex-wrap: wrap; gap: 15px;">
        ${Object.entries(stats.errorsByType).map(([type, count]) => `
          <div style="flex: 1; min-width: 200px; padding: 15px; background: #f7fafc; border-radius: 8px;">
            <div style="font-weight: 600; color: #4a5568;">${type}</div>
            <div style="font-size: 1.5em; color: #e53e3e; margin-top: 5px;">${count}</div>
          </div>
        `).join('')}
      </div>
    </div>
    
    <footer class="footer">
      <p>Report generated by TaRL Assessment System Automated Testing Suite</p>
      <p>Powered by Playwright Test Framework</p>
    </footer>
  </div>
</body>
</html>
  `;
  
  const reportPath = path.join(reportDir, 'comprehensive-page-inspection-report.html');
  fs.writeFileSync(reportPath, htmlReport);
  
  // Also save JSON report for programmatic access
  const jsonReport = {
    timestamp,
    stats,
    results: allResults
  };
  
  fs.writeFileSync(
    path.join(reportDir, 'comprehensive-page-inspection-report.json'),
    JSON.stringify(jsonReport, null, 2)
  );
  
  return reportPath;
}

// Main test suite
test.describe('Comprehensive Page Inspection', () => {
  test.setTimeout(600000); // 10 minutes for comprehensive testing
  
  const allResults = [];
  
  for (const [role, routes] of Object.entries(ALL_ROUTES)) {
    test(`${role} - Full page inspection`, async ({ page }) => {
      const roleResult = {
        role,
        pages: [],
        summary: {
          total: routes.length,
          passed: 0,
          failed: 0
        }
      };
      
      console.log(`\n🔍 Testing ${role.toUpperCase()} role (${routes.length} pages)`);
      
      try {
        // Login
        await login(page, role);
        console.log(`✓ Logged in as ${role}`);
        
        // Test each route
        for (const route of routes) {
          console.log(`  Testing: ${route.name} (${route.path})`);
          
          const pageResult = {
            name: route.name,
            path: route.path,
            errors: [],
            screenshot: null
          };
          
          // Check page for errors
          const errors = await checkPageForErrors(page, route);
          pageResult.errors = errors;
          
          if (errors.length > 0) {
            // Take screenshot if errors found
            pageResult.screenshot = await captureScreenshot(
              page, 
              `${role}_${route.path.replace(/\//g, '_')}`, 
              'page-errors'
            );
            
            roleResult.summary.failed++;
            console.log(`    ✗ ${errors.length} error(s) found`);
            errors.forEach(err => {
              console.log(`      - [${err.severity.toUpperCase()}] ${err.type}: ${err.message.substring(0, 100)}`);
            });
          } else {
            roleResult.summary.passed++;
            console.log(`    ✓ No errors found`);
          }
          
          roleResult.pages.push(pageResult);
        }
        
        // Logout
        await logout(page);
        console.log(`✓ Logged out ${role}`);
        
      } catch (error) {
        console.error(`✗ Test failed for ${role}: ${error.message}`);
        roleResult.error = error.message;
      }
      
      allResults.push(roleResult);
      
      // Summary for this role
      console.log(`\n📊 ${role.toUpperCase()} Summary:`);
      console.log(`   Total: ${roleResult.summary.total}`);
      console.log(`   Passed: ${roleResult.summary.passed}`);
      console.log(`   Failed: ${roleResult.summary.failed}`);
      
      // Assert no critical errors
      const criticalErrors = roleResult.pages.reduce((count, page) => 
        count + page.errors.filter(e => e.severity === 'high').length, 0
      );
      
      if (criticalErrors > 0) {
        console.error(`⚠️  Found ${criticalErrors} critical error(s) for ${role}`);
      }
      
      expect(criticalErrors).toBe(0);
    });
  }
  
  test.afterAll(async () => {
    // Generate comprehensive report
    const reportPath = await generateDetailedReport(allResults);
    console.log(`\n📋 Comprehensive report generated: ${reportPath}`);
    
    // Print final summary
    const totalErrors = allResults.reduce((sum, r) => 
      sum + r.pages.reduce((pageSum, p) => pageSum + p.errors.length, 0), 0
    );
    
    console.log('\n' + '='.repeat(60));
    console.log('FINAL TEST SUMMARY');
    console.log('='.repeat(60));
    console.log(`Total Roles Tested: ${allResults.length}`);
    console.log(`Total Pages Tested: ${allResults.reduce((sum, r) => sum + r.pages.length, 0)}`);
    console.log(`Total Errors Found: ${totalErrors}`);
    
    if (totalErrors === 0) {
      console.log('\n✅ All pages passed inspection!');
    } else {
      console.log('\n⚠️  Issues found - review the report for details');
    }
  });
});