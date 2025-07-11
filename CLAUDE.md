# Claude Code Configuration

## Project Commands

### Code Quality Checks
```bash
# PHP Code Style Check (Laravel Pint)
./vendor/bin/pint --test

# Run PHP Code Style Fix
./vendor/bin/pint

# Run PHPStan Static Analysis
./vendor/bin/phpstan analyse

# Run Tests
php artisan test
```

### Database Commands
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seeders
php artisan migrate:fresh --seed
```

### Development Server
```bash
# Start development server
php artisan serve --port=8001
```

## Project Structure

This is a Laravel-based TaRL (Teaching at the Right Level) assessment system with the following key features:

- **User Management**: Admin can manage users with different roles (admin, teacher, mentor)
- **School Management**: CRUD operations for schools
- **Student Management**: Track students with photos, grades, and assessment data
- **Assessment System**: Baseline, Midline, and Endline assessments
- **Mentoring Visits**: Document and track mentoring activities
- **Reporting**: Various reports for tracking student progress

## Key Files Modified

- Navigation menu: `/resources/views/layouts/navigation.blade.php`
- Student management: `/app/Http/Controllers/StudentController.php`
- User management: `/app/Http/Controllers/UserController.php`
- School management: `/app/Http/Controllers/SchoolController.php`
- Settings: `/app/Http/Controllers/SettingController.php`
- Help system: `/app/Http/Controllers/HelpController.php`