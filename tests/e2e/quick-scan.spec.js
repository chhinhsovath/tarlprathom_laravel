import { test, expect } from '@playwright/test';
import { login, logout } from './helpers/test-helpers.js';

test.describe('Quick Issue Scan', () => {
  test.setTimeout(120000);
  
  test('Admin - Quick scan for critical issues', async ({ page }) => {
    const criticalPages = [
      '/dashboard',
      '/students',
      '/schools',
      '/classes',
      '/reports',
      '/help',
      '/settings'
    ];
    
    const issues = [];
    
    // Login as admin
    await login(page, 'admin');
    
    for (const pagePath of criticalPages) {
      console.log(`Checking ${pagePath}...`);
      
      try {
        const response = await page.goto(pagePath, { 
          waitUntil: 'domcontentloaded',
          timeout: 15000 
        });
        
        const status = response.status();
        const bodyText = await page.locator('body').innerText().catch(() => '');
        
        // Check for errors
        const hasError = 
          status >= 400 ||
          bodyText.includes('500') ||
          bodyText.includes('404') ||
          bodyText.includes('SQLSTATE') ||
          bodyText.includes('Exception') ||
          bodyText.includes('Error') ||
          bodyText.includes('Undefined');
        
        // Check for Khmer in English mode
        const khmerInEnglish = /[\u1780-\u17FF]/.test(bodyText) && bodyText.length > 100;
        
        if (hasError || khmerInEnglish) {
          issues.push({
            page: pagePath,
            status,
            hasError,
            khmerInEnglish,
            errorSnippet: bodyText.substring(0, 200)
          });
          
          console.log(`  ✗ Issues found on ${pagePath}`);
          if (hasError) console.log(`    - HTTP ${status} or error detected`);
          if (khmerInEnglish) console.log(`    - Khmer text in English mode`);
        } else {
          console.log(`  ✓ ${pagePath} OK`);
        }
        
      } catch (error) {
        issues.push({
          page: pagePath,
          error: error.message
        });
        console.log(`  ✗ Failed to load ${pagePath}: ${error.message}`);
      }
    }
    
    // Summary
    console.log('\n=== QUICK SCAN SUMMARY ===');
    console.log(`Total pages checked: ${criticalPages.length}`);
    console.log(`Issues found: ${issues.length}`);
    
    if (issues.length > 0) {
      console.log('\nPages with issues:');
      issues.forEach(issue => {
        console.log(`- ${issue.page}`);
        if (issue.hasError) console.log(`  Error: ${issue.errorSnippet.substring(0, 100)}...`);
        if (issue.khmerInEnglish) console.log(`  Localization: Khmer text in English mode`);
        if (issue.error) console.log(`  Load error: ${issue.error}`);
      });
    }
    
    await logout(page);
  });
});