# TaRL Assessment System - Production Deployment Guide

## Overview
This guide provides instructions for deploying the updated TaRL Assessment System to the production server at https://plp.moeys.gov.kh/tarl/public/

## Current Status

### Data Statistics
- **Total Schools**: 7,380 (from tbl_tarl_schools)
- **Total Students**: 500 (demo data)
- **Total Assessments**: 2,140
- **Total Users**: 6 system users

### Assessment Levels Updated
#### Khmer (7 levels)
- Beginner
- Letter
- Word
- Paragraph
- Story
- Comp. 1
- Comp. 2

#### Math (6 levels)
- Beginner
- 1-Digit
- 2-Digit
- Subtraction
- Division
- Word Problem

## Deployment Steps

### 1. Pre-Deployment Checklist
- [ ] Backup current production database
- [ ] Test all features in staging environment
- [ ] Review security settings (APP_DEBUG=false)
- [ ] Clear all caches

### 2. Code Deployment

```bash
# Pull latest changes from repository
git pull origin main

# Install/update dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 3. Database Updates

```bash
# Run migrations (if any)
php artisan migrate --force

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4. Data Synchronization

The system now uses `tbl_tarl_schools` table for school information. This table contains 7,380 schools with proper province/district mappings.

#### Update Province/District Names (if needed)
```bash
# This updates empty province/district names from Geographic table
php artisan tinker
>>> $schools = \DB::table('tbl_tarl_schools')
...     ->whereNull('sclProvinceName')
...     ->orWhere('sclProvinceName', '')
...     ->get();
>>> // Update logic here
```

### 5. Public Display Configuration

The public assessment results page (`/`) displays:
- Bar charts for Khmer and Math assessments
- All 7 Khmer levels and 6 Math levels
- Cycle data (Baseline, Midline, Endline)
- Total students assessed

#### API Endpoint
- **URL**: `/api/assessment-data`
- **Parameters**: `subject` (khmer|math)
- **Returns**: Chart data with labels, counts, and cycle statistics

### 6. User Accounts

Ensure these accounts are active:
- **Coordinator**: coordinator@prathaminternational.org (password: admin123)
- **Admin**: kairav@prathaminternational.org (password: admin123)
- **Mentor**: mentor1@prathaminternational.org (password: admin123)
- **Teacher**: teacher1@prathaminternational.org (password: admin123)
- **Viewer**: viewer@prathaminternational.org (password: admin123)

### 7. Security Considerations

1. **Environment Variables**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://plp.moeys.gov.kh/tarl
   ```

2. **HTTPS Enforcement**
   - Ensure SSL certificate is valid
   - Force HTTPS in .htaccess or nginx config

3. **Rate Limiting**
   - Authentication routes have throttle middleware
   - API endpoints should have rate limiting

### 8. Post-Deployment Testing

1. **Public View**
   - Visit https://plp.moeys.gov.kh/tarl/public/
   - Verify chart displays correctly
   - Test subject switching (Khmer/Math)
   - Check data accuracy

2. **Admin Functions**
   - Login as admin
   - Create test assessment
   - Verify reports generation
   - Check user management

3. **Teacher Functions**
   - Login as teacher
   - Create student assessment
   - View class reports
   - Verify access restrictions

### 9. Monitoring

Set up monitoring for:
- Application errors (Laravel log)
- Database performance
- API response times
- User activity

### 10. Rollback Plan

If issues occur:
1. Restore database backup
2. Revert to previous code version
3. Clear all caches
4. Restart web server

## Data Export for Production

To export current data for production:

```bash
# Export assessments
php artisan tinker
>>> \App\Models\Assessment::with('student')->get()->toJson();

# Export students
>>> \App\Models\Student::all()->toJson();

# Export schools (already in tbl_tarl_schools)
>>> \App\Models\School::all()->toJson();
```

## Important Notes

1. **School Table Migration**: The system now uses `tbl_tarl_schools` instead of `schools` table
2. **Geographic Data**: Province/district names are fetched from `geographic` table
3. **Assessment Levels**: All 7 Khmer and 6 Math levels are now supported
4. **Public Access**: Public view shows all data without authentication

## Troubleshooting

### Common Issues

1. **Chart not displaying**
   - Check browser console for JavaScript errors
   - Verify API endpoint is accessible
   - Clear browser cache

2. **Missing data**
   - Verify database connections
   - Check foreign key constraints
   - Review error logs

3. **Permission errors**
   - Fix storage permissions
   - Clear application cache
   - Check web server user

## Contact

For deployment support, contact the development team.

---

Last Updated: August 9, 2025
Version: 2.0.0