import { test, expect } from '@playwright/test';
import { login, logout, captureScreenshot } from './helpers/test-helpers.js';

// Define all text patterns for each language
const LANGUAGE_PATTERNS = {
  en: {
    // Common navigation and UI elements
    patterns: [
      /Dashboard/i,
      /Students?/i,
      /Assessments?/i,
      /Reports?/i,
      /Schools?/i,
      /Classes?/i,
      /Settings?/i,
      /Profile/i,
      /Log\s?Out/i,
      /Search/i,
      /Filter/i,
      /Actions?/i,
      /Edit/i,
      /Delete/i,
      /Create/i,
      /Add/i,
      /Save/i,
      /Cancel/i,
      /Submit/i,
      /Export/i,
      /Import/i,
      /Download/i,
      /Upload/i,
      /Select/i,
      /View/i,
      /Name/i,
      /Email/i,
      /Phone/i,
      /Address/i,
      /Status/i,
      /Date/i,
      /Time/i,
      /Total/i,
      /Results?/i,
      /Performance/i,
      /Progress/i,
      /Baseline/i,
      /Midline/i,
      /Endline/i
    ],
    // Should NOT contain Khmer characters
    invalidPatterns: [
      /[\u1780-\u17FF]/  // Khmer Unicode range
    ]
  },
  km: {
    // Common Khmer UI elements and translations
    patterns: [
      /ផ្ទាំងគ្រប់គ្រង/,  // Dashboard
      /សិស្ស/,            // Students
      /ការវាយតម្លៃ/,     // Assessments
      /របាយការណ៍/,       // Reports
      /សាលារៀន/,         // Schools
      /ថ្នាក់រៀន/,       // Classes
      /ការកំណត់/,        // Settings
      /ប្រវត្តិរូប/,     // Profile
      /ចេញ/,             // Log Out
      /ស្វែងរក/,         // Search
      /ច្រោះ/,           // Filter
      /សកម្មភាព/,        // Actions
      /កែសម្រួល/,        // Edit
      /លុប/,             // Delete
      /បង្កើត/,          // Create
      /បន្ថែម/,          // Add
      /រក្សាទុក/,        // Save
      /បោះបង់/,          // Cancel
      /ដាក់ស្នើ/,        // Submit
      /នាំចេញ/,          // Export
      /នាំចូល/,          // Import
      /ទាញយក/,           // Download
      /ផ្ទុកឡើង/,        // Upload
      /ជ្រើសរើស/,        // Select
      /មើល/,             // View
      /ឈ្មោះ/,           // Name
      /អ៊ីមែល/,          // Email
      /ទូរស័ព្ទ/,        // Phone
      /អាសយដ្ឋាន/,       // Address
      /ស្ថានភាព/,        // Status
      /កាលបរិច្ឆេទ/,     // Date
      /ពេលវេលា/,         // Time
      /សរុប/,            // Total
      /លទ្ធផល/,          // Results
      /ការអនុវត្ត/,     // Performance
      /វឌ្ឍនភាព/,        // Progress
      /មូលដ្ឋាន/,        // Baseline
      /ពាក់កណ្តាល/,      // Midline
      /ចុងក្រោយ/         // Endline
    ],
    // Should NOT contain English text (except for technical terms)
    invalidPatterns: [
      // Check for common English words that should be translated
      /\b(Dashboard|Student|Assessment|Report|School|Class|Setting|Profile)\b/i,
      /\b(Search|Filter|Action|Edit|Delete|Create|Add|Save|Cancel|Submit)\b/i,
      /\b(Export|Import|Download|Upload|Select|View|Name|Email|Phone|Address)\b/i,
      /\b(Status|Date|Time|Total|Result|Performance|Progress)\b/i
    ]
  }
};

// All pages to test for each role
const PAGES_TO_TEST = {
  admin: [
    '/dashboard',
    '/students',
    '/students/create',
    '/assessments',
    '/assessments/create',
    '/assessments/select-students',
    '/mentoring',
    '/mentoring/create',
    '/reports',
    '/reports/student-performance',
    '/reports/progress-tracking',
    '/reports/school-comparison',
    '/reports/performance-calculation',
    '/reports/my-students',
    '/schools',
    '/schools/create',
    '/schools/assessment-dates',
    '/classes',
    '/classes/create',
    '/users',
    '/users/create',
    '/users/assign-schools',
    '/administration',
    '/assessment-management',
    '/assessment-management/mentoring-visits',
    '/settings',
    '/help',
    '/profile'
  ],
  mentor: [
    '/dashboard',
    '/students',
    '/students/create',
    '/assessments',
    '/assessments/create',
    '/assessments/select-students',
    '/mentoring',
    '/mentoring/create',
    '/reports',
    '/reports/student-performance',
    '/reports/progress-tracking',
    '/reports/my-mentoring',
    '/schools',
    '/profile'
  ],
  teacher: [
    '/dashboard',
    '/students',
    '/students/create',
    '/assessments',
    '/assessments/create',
    '/classes',
    '/classes/create',
    '/reports/my-students',
    '/reports/class-progress',
    '/profile'
  ],
  coordinator: [
    '/dashboard',
    '/students',
    '/assessments',
    '/mentoring',
    '/reports',
    '/schools',
    '/users',
    '/administration',
    '/coordinator',
    '/settings',
    '/help'
  ],
  viewer: [
    '/dashboard',
    '/reports',
    '/reports/student-performance',
    '/reports/progress-tracking',
    '/profile'
  ]
};

// Helper function to check language consistency
async function checkLanguageConsistency(page, language, pagePath) {
  const errors = [];
  const bodyText = await page.locator('body').innerText();
  
  // Skip empty pages or error pages
  if (bodyText.includes('404') || bodyText.includes('403') || bodyText.includes('500')) {
    return { errors: [`Page returned error: ${bodyText.substring(0, 100)}`], bodyText };
  }
  
  const langConfig = LANGUAGE_PATTERNS[language];
  
  // Check for expected patterns
  let hasExpectedContent = false;
  for (const pattern of langConfig.patterns) {
    if (pattern.test(bodyText)) {
      hasExpectedContent = true;
      break;
    }
  }
  
  // Check for invalid patterns (wrong language)
  for (const pattern of langConfig.invalidPatterns) {
    if (pattern.test(bodyText)) {
      const matches = bodyText.match(pattern);
      errors.push(`Found invalid content for ${language}: "${matches[0].substring(0, 50)}..."`);
    }
  }
  
  // Additional checks for Khmer
  if (language === 'km') {
    // Check if page has substantial Khmer content
    const khmerChars = bodyText.match(/[\u1780-\u17FF]/g);
    const totalChars = bodyText.length;
    const khmerPercentage = khmerChars ? (khmerChars.length / totalChars) * 100 : 0;
    
    if (khmerPercentage < 10 && totalChars > 100) {
      errors.push(`Page has insufficient Khmer content (${khmerPercentage.toFixed(1)}% Khmer characters)`);
    }
    
    // Check for untranslated English UI elements
    const commonEnglishUI = [
      'Dashboard', 'Students', 'Assessments', 'Reports', 'Schools',
      'Settings', 'Profile', 'Search', 'Filter', 'Actions',
      'Edit', 'Delete', 'Create', 'Add', 'Save', 'Cancel'
    ];
    
    for (const term of commonEnglishUI) {
      // Allow technical terms in parentheses or as part of IDs/classes
      const regex = new RegExp(`\\b${term}\\b(?![\\w-]|[^<]*>)`, 'i');
      if (regex.test(bodyText)) {
        // Check if it's not in a technical context
        const context = bodyText.match(new RegExp(`.{0,30}${term}.{0,30}`, 'i'));
        if (context && !context[0].includes('(') && !context[0].includes('id=') && !context[0].includes('class=')) {
          errors.push(`Found untranslated UI element: "${term}"`);
        }
      }
    }
  }
  
  // Additional checks for English
  if (language === 'en') {
    // Check for Khmer characters that shouldn't be there
    const khmerChars = bodyText.match(/[\u1780-\u17FF]/g);
    if (khmerChars && khmerChars.length > 5) { // Allow a few Khmer chars for names
      errors.push(`Found Khmer characters in English mode (${khmerChars.length} characters)`);
    }
  }
  
  return { errors, bodyText, hasExpectedContent };
}

// Test each role with each language
test.describe('Comprehensive Localization Tests', () => {
  test.setTimeout(300000); // 5 minutes timeout for comprehensive tests
  
  const languages = ['en', 'km'];
  const roles = ['admin', 'mentor', 'teacher', 'coordinator', 'viewer'];
  
  for (const language of languages) {
    for (const role of roles) {
      test(`${role} - ${language.toUpperCase()} language consistency`, async ({ page }) => {
        const results = {
          role,
          language,
          totalPages: 0,
          passedPages: [],
          failedPages: [],
          errors: []
        };
        
        try {
          // Login as the role
          await login(page, role);
          
          // Set language
          const langSwitchUrl = language === 'km' ? '/language/km' : '/language/en';
          await page.goto(langSwitchUrl);
          await page.waitForTimeout(1000); // Wait for language to apply
          
          // Test each page for this role
          const pagesToTest = PAGES_TO_TEST[role];
          results.totalPages = pagesToTest.length;
          
          for (const pagePath of pagesToTest) {
            try {
              console.log(`Testing ${role} - ${language}: ${pagePath}`);
              
              // Navigate to page
              await page.goto(pagePath, { waitUntil: 'networkidle', timeout: 30000 });
              await page.waitForTimeout(1000); // Wait for dynamic content
              
              // Check language consistency
              const { errors, hasExpectedContent } = await checkLanguageConsistency(page, language, pagePath);
              
              if (errors.length === 0 && hasExpectedContent) {
                results.passedPages.push(pagePath);
                console.log(`✓ ${pagePath} - Language check passed`);
              } else {
                results.failedPages.push(pagePath);
                results.errors.push({
                  page: pagePath,
                  errors: errors.length > 0 ? errors : ['No expected language content found'],
                  screenshot: await captureScreenshot(page, `${role}_${language}_${pagePath.replace(/\//g, '_')}`, 'localization-errors')
                });
                console.log(`✗ ${pagePath} - Language check failed:`, errors);
              }
            } catch (error) {
              results.failedPages.push(pagePath);
              results.errors.push({
                page: pagePath,
                errors: [`Page error: ${error.message}`],
                screenshot: await captureScreenshot(page, `${role}_${language}_${pagePath.replace(/\//g, '_')}_error`, 'localization-errors')
              });
              console.log(`✗ ${pagePath} - Error:`, error.message);
            }
          }
          
          // Logout
          await logout(page);
          
        } catch (error) {
          results.errors.push({
            page: 'general',
            errors: [`Test setup error: ${error.message}`]
          });
        }
        
        // Generate report
        console.log('\n=== Localization Test Results ===');
        console.log(`Role: ${role}, Language: ${language}`);
        console.log(`Total Pages: ${results.totalPages}`);
        console.log(`Passed: ${results.passedPages.length}`);
        console.log(`Failed: ${results.failedPages.length}`);
        
        if (results.failedPages.length > 0) {
          console.log('\nFailed Pages:');
          results.errors.forEach(err => {
            console.log(`  ${err.page}:`);
            err.errors.forEach(e => console.log(`    - ${e}`));
          });
        }
        
        // Assert no failures for CI
        expect(results.failedPages.length).toBe(0);
      });
    }
  }
  
  // Special test for language switching
  test('Language switching persistence', async ({ page }) => {
    await login(page, 'admin');
    
    // Switch to Khmer
    await page.goto('/language/km');
    await page.waitForTimeout(1000);
    
    // Navigate to different pages and verify language persists
    const testPages = ['/dashboard', '/students', '/reports'];
    
    for (const testPage of testPages) {
      await page.goto(testPage);
      const { errors } = await checkLanguageConsistency(page, 'km', testPage);
      expect(errors.length).toBe(0);
    }
    
    // Switch to English
    await page.goto('/language/en');
    await page.waitForTimeout(1000);
    
    for (const testPage of testPages) {
      await page.goto(testPage);
      const { errors } = await checkLanguageConsistency(page, 'en', testPage);
      expect(errors.length).toBe(0);
    }
    
    await logout(page);
  });
});