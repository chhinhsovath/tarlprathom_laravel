#!/bin/bash

echo "================================"
echo "Preparing for Git Commit"
echo "================================"

# 1. Check for syntax errors
echo "1. Checking PHP syntax..."
find app database -name "*.php" -print0 | xargs -0 -n1 -P8 php -l | grep -v "No syntax errors"
if [ $? -eq 0 ]; then
    echo "❌ PHP syntax errors found!"
    exit 1
fi
echo "✅ PHP syntax check passed"

# 2. Check for debug statements
echo -e "\n2. Checking for debug statements..."
if grep -r "dd(" app/ resources/views/ --include="*.php" --include="*.blade.php"; then
    echo "❌ Found dd() statements!"
    exit 1
fi

if grep -r "dump(" app/ resources/views/ --include="*.php" --include="*.blade.php"; then
    echo "❌ Found dump() statements!"
    exit 1
fi

if grep -r "console.log" resources/js/ public/js/ --include="*.js"; then
    echo "❌ Found console.log statements!"
    exit 1
fi
echo "✅ No debug statements found"

# 3. Format code
echo -e "\n3. Formatting code with Laravel Pint..."
if [ -f "./vendor/bin/pint" ]; then
    ./vendor/bin/pint
    echo "✅ Code formatted"
else
    echo "⚠️  Laravel Pint not installed"
fi

# 4. Clear caches
echo -e "\n4. Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "✅ Caches cleared"

# 5. Check migrations
echo -e "\n5. Checking migrations..."
php artisan migrate:status
echo "✅ Migration status checked"

# 6. Generate optimized autoload
echo -e "\n6. Optimizing autoload..."
composer dump-autoload --optimize
echo "✅ Autoload optimized"

# 7. Build assets (if needed)
if [ -f "package.json" ]; then
    echo -e "\n7. Building assets..."
    npm run build
    if [ $? -eq 0 ]; then
        echo "✅ Assets built successfully"
    else
        echo "❌ Asset build failed!"
        exit 1
    fi
fi

# 8. Summary
echo -e "\n================================"
echo "Pre-commit checks completed!"
echo "================================"
echo ""
echo "Ready to commit. Suggested commit message format:"
echo "feat: Add advanced search, filters, and Excel export to all controllers"
echo ""
echo "Changes include:"
echo "- Added grade and gender filters to StudentController"
echo "- Added search functionality to AssessmentController"
echo "- Added search functionality to MentoringVisitController"
echo "- Implemented Excel export for all modules"
echo "- Created comprehensive workflow documentation"
echo "- Added testing infrastructure with factories"
echo ""
echo "To commit:"
echo "git add -A"
echo "git commit -m \"feat: Add advanced search, filters, and Excel export to all controllers\""
echo ""
echo "Don't forget to:"
echo "1. Review changed files: git status"
echo "2. Test critical features locally"
echo "3. Update .env.example if needed"
echo "4. Document any new environment variables"