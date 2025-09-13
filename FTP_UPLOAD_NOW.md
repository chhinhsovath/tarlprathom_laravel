# URGENT: FTP Upload Instructions to Fix Production Error

## The Error:
`Call to undefined function trans_km()` on https://tarl.dashboardkh.com/teacher/profile/setup

## Quick Fix - Upload These 4 Files NOW:

### 1. CREATE NEW FILE: `resources/views/components/translations.blade.php`
Upload the file from your local project to:
```
/home/dashfiyn/tarl.dashboardkh.com/resources/views/components/translations.blade.php
```

### 2. UPDATE: `resources/views/teacher/profile-setup.blade.php`
Replace the existing file at:
```
/home/dashfiyn/tarl.dashboardkh.com/resources/views/teacher/profile-setup.blade.php
```

### 3. UPDATE: `resources/lang/km.json`
Replace the existing file at:
```
/home/dashfiyn/tarl.dashboardkh.com/resources/lang/km.json
```

### 4. UPDATE: `app/Http/Controllers/TeacherProfileController.php`
Replace the existing file at:
```
/home/dashfiyn/tarl.dashboardkh.com/app/Http/Controllers/TeacherProfileController.php
```
(This file has the SQL fixes for 'class' vs 'grade' column)

## After Uploading:

1. Go to: https://tarl.dashboardkh.com/deploy-helper.php?token=deploy2024tarl
2. Click "🧹 Clear Caches Only" button
3. Test the page: https://tarl.dashboardkh.com/teacher/profile/setup

## Additional Files to Fix Student Management:

### 5. CREATE NEW FILE: `resources/views/teacher/manage-students-fixed.blade.php`
Upload to:
```
/home/dashfiyn/tarl.dashboardkh.com/resources/views/teacher/manage-students-fixed.blade.php
```

### 6. UPDATE: `app/Http/Controllers/StudentController.php`
Replace at:
```
/home/dashfiyn/tarl.dashboardkh.com/app/Http/Controllers/StudentController.php
```

### 7. UPDATE: `app/Models/Student.php`
Replace at:
```
/home/dashfiyn/tarl.dashboardkh.com/app/Models/Student.php
```

## Summary of Changes:
- Fixed trans_km() function by including it in Blade component
- Fixed SQL errors (changed 'grade' to 'class' column references)
- Created cleaner student management view
- Added Khmer translations

## IMPORTANT:
Upload files #1-4 first to fix the immediate error, then upload #5-7 for student management fixes.