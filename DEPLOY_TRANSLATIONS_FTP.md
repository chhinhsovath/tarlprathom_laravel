# FTP Deployment Guide for Khmer Translations Fix

## SIMPLIFIED SOLUTION - No Composer Required!

### Files to Upload via FTP (Simple Version):

#### 1. NEW Component File (MUST CREATE):
- `resources/views/components/translations.blade.php` - Translation helper component

#### 2. Modified View Files (MUST UPDATE):
- `resources/views/teacher/profile-setup.blade.php` - Includes translation component
- `resources/views/test-language.blade.php` - Includes translation component
- `resources/lang/km.json` - Khmer translations

#### 3. Optional (for future use):
- `app/Helpers/helpers.php` - Updated trans_db function
- `public/deploy-helper.php` - Updated with Dump Autoload button

### Upload these files via FTP:

```
1. CREATE this new file on server:
   resources/views/components/translations.blade.php

2. UPDATE these existing files:
   resources/views/teacher/profile-setup.blade.php
   resources/views/test-language.blade.php
   resources/lang/km.json
```

### Full Server Paths:
```
/home/dashfiyn/tarl.dashboardkh.com/resources/views/components/translations.blade.php (NEW)
/home/dashfiyn/tarl.dashboardkh.com/resources/views/teacher/profile-setup.blade.php
/home/dashfiyn/tarl.dashboardkh.com/resources/views/test-language.blade.php
/home/dashfiyn/tarl.dashboardkh.com/resources/lang/km.json
```

## After Upload Steps:

1. Visit: https://tarl.dashboardkh.com/deploy-helper.php?token=deploy2024tarl
2. Click "🧹 Clear Caches Only"
3. Test the application

## Verification:
After deployment, test by visiting:
- https://tarl.dashboardkh.com/teacher/profile/setup
- The page should show Khmer text when locale is set to 'km'

## This Solution Works Because:
- No composer autoload needed
- Translation function is defined directly in the Blade component
- Component is included at the top of each view that needs translations
- Works immediately after clearing view cache