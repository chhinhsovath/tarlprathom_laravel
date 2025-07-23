# Testing & Deployment Workflow - Tarlprathom Laravel

## 1. Pre-Commit Testing Checklist

### Local Testing Script

Create `.github/pre-push.sh`:

```bash
#!/bin/bash

echo "Running pre-push checks..."

# 1. PHP Syntax Check
echo "Checking PHP syntax..."
find app -name "*.php" -exec php -l {} \; | grep -v "No syntax errors"

# 2. Run PHPUnit Tests
echo "Running tests..."
php artisan test

# 3. Check Code Style
echo "Checking code style..."
./vendor/bin/pint --test

# 4. Check for dump/dd statements
echo "Checking for debug statements..."
grep -r "dd(" app/ && echo "Found dd() statements!" && exit 1
grep -r "dump(" app/ && echo "Found dump() statements!" && exit 1

# 5. Check for console.log
echo "Checking for console.log..."
grep -r "console.log" resources/js/ && echo "Found console.log statements!" && exit 1

# 6. Run npm build
echo "Building assets..."
npm run build

echo "All checks passed!"
```

### Git Hooks Setup

Create `.git/hooks/pre-commit`:

```bash
#!/bin/bash

# Run Laravel Pint
./vendor/bin/pint

# Add formatted files
git add -A

# Run tests
php artisan test --stop-on-failure
```

## 2. Testing Workflow

### A. Unit Testing Structure

Create test files following this pattern:

```php
// tests/Feature/StudentManagementTest.php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $teacher;
    protected $school;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->school = School::factory()->create();
        $this->admin = User::factory()->admin()->create();
        $this->teacher = User::factory()->teacher()->create([
            'school_id' => $this->school->id
        ]);
    }

    /** @test */
    public function admin_can_view_all_students()
    {
        $students = Student::factory()->count(5)->create();
        
        $response = $this->actingAs($this->admin)
            ->get('/students');
            
        $response->assertStatus(200);
        foreach ($students as $student) {
            $response->assertSee($student->name);
        }
    }

    /** @test */
    public function teacher_can_only_view_own_school_students()
    {
        $ownStudents = Student::factory()->count(3)->create([
            'school_id' => $this->school->id
        ]);
        
        $otherStudents = Student::factory()->count(2)->create([
            'school_id' => School::factory()->create()->id
        ]);
        
        $response = $this->actingAs($this->teacher)
            ->get('/students');
            
        $response->assertStatus(200);
        
        foreach ($ownStudents as $student) {
            $response->assertSee($student->name);
        }
        
        foreach ($otherStudents as $student) {
            $response->assertDontSee($student->name);
        }
    }

    /** @test */
    public function student_creation_requires_valid_data()
    {
        $response = $this->actingAs($this->admin)
            ->post('/students', [
                'name' => '',
                'grade' => 'invalid',
                'gender' => 'other'
            ]);
            
        $response->assertSessionHasErrors(['name', 'grade', 'gender']);
    }

    /** @test */
    public function ajax_assessment_save_works()
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id
        ]);
        
        $response = $this->actingAs($this->teacher)
            ->postJson('/api/assessments/save-student', [
                'student_id' => $student->id,
                'subject' => 'khmer',
                'cycle' => 'baseline',
                'level' => 'Word',
                'gender' => 'male'
            ]);
            
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Assessment saved successfully'
            ]);
            
        $this->assertDatabaseHas('assessments', [
            'student_id' => $student->id,
            'subject' => 'khmer',
            'level' => 'Word'
        ]);
    }
}
```

### B. JavaScript Testing

Create `tests/js/assessment.test.js`:

```javascript
// Mock jQuery AJAX
const mockAjax = (options) => {
    if (options.url === '/api/assessments/save-student') {
        if (options.data.level === 'Invalid Level') {
            options.error({
                status: 422,
                responseJSON: {
                    errors: {
                        level: ['Invalid level for subject']
                    }
                }
            });
        } else {
            options.success({
                success: true,
                message: 'Assessment saved successfully'
            });
        }
    }
};

describe('Assessment Module', () => {
    beforeEach(() => {
        global.$ = {
            ajax: mockAjax,
            post: jest.fn()
        };
    });

    test('saves valid assessment', (done) => {
        $.ajax({
            url: '/api/assessments/save-student',
            data: {
                student_id: 1,
                subject: 'khmer',
                level: 'Word'
            },
            success: (response) => {
                expect(response.success).toBe(true);
                done();
            }
        });
    });

    test('handles validation errors', (done) => {
        $.ajax({
            url: '/api/assessments/save-student',
            data: {
                student_id: 1,
                subject: 'khmer',
                level: 'Invalid Level'
            },
            error: (xhr) => {
                expect(xhr.status).toBe(422);
                expect(xhr.responseJSON.errors.level).toBeDefined();
                done();
            }
        });
    });
});
```

### C. Integration Testing

Create `tests/Integration/AssessmentFlowTest.php`:

```php
<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\School;
use App\Models\Assessment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssessmentFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function complete_assessment_flow_works()
    {
        // Setup
        $school = School::factory()->create();
        $teacher = User::factory()->teacher()->create(['school_id' => $school->id]);
        $students = Student::factory()->count(5)->create(['school_id' => $school->id]);

        // 1. Teacher navigates to assessment page
        $response = $this->actingAs($teacher)
            ->get('/assessments/create?subject=khmer&cycle=baseline');
            
        $response->assertStatus(200);
        foreach ($students as $student) {
            $response->assertSee($student->name);
        }

        // 2. Save individual assessments via AJAX
        foreach ($students as $index => $student) {
            $levels = ['Beginner', 'Reader', 'Word'];
            $level = $levels[$index % 3];
            
            $response = $this->actingAs($teacher)
                ->postJson('/api/assessments/save-student', [
                    'student_id' => $student->id,
                    'subject' => 'khmer',
                    'cycle' => 'baseline',
                    'level' => $level,
                    'gender' => $student->gender
                ]);
                
            $response->assertStatus(200);
        }

        // 3. Submit all assessments
        $response = $this->actingAs($teacher)
            ->postJson('/api/assessments/submit-all', [
                'subject' => 'khmer',
                'cycle' => 'baseline',
                'submitted_count' => 5
            ]);
            
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => route('dashboard')
            ]);

        // 4. Verify all assessments saved
        $this->assertEquals(5, Assessment::count());
        
        // 5. Check dashboard shows results
        $response = $this->actingAs($teacher)
            ->get('/dashboard');
            
        $response->assertStatus(200);
    }
}
```

## 3. Deployment Process

### A. Deployment Checklist

Create `DEPLOYMENT_CHECKLIST.md`:

```markdown
# Deployment Checklist

## Pre-Deployment
- [ ] All tests passing locally
- [ ] Code reviewed and approved
- [ ] Database migrations tested on staging
- [ ] Asset build successful (`npm run build`)
- [ ] No debug statements (dd, dump, console.log)
- [ ] Environment variables documented
- [ ] Backup current production database

## Deployment Steps
1. [ ] Put application in maintenance mode
2. [ ] Pull latest code
3. [ ] Install composer dependencies
4. [ ] Run database migrations
5. [ ] Clear all caches
6. [ ] Build frontend assets
7. [ ] Restart queue workers
8. [ ] Disable maintenance mode
9. [ ] Verify deployment

## Post-Deployment
- [ ] Test critical user flows
- [ ] Check error logs
- [ ] Monitor performance
- [ ] Notify team of completion
```

### B. Deployment Script

Create `deploy.sh`:

```bash
#!/bin/bash

# Configuration
APP_PATH="/var/www/tarlprathom"
BACKUP_PATH="/backups"
LOG_FILE="/var/log/deployments/deploy_$(date +%Y%m%d_%H%M%S).log"

# Functions
log() {
    echo "[$(date +'%Y-%m-%d %H:%M:%S')] $1" | tee -a $LOG_FILE
}

# Start deployment
log "Starting deployment..."

# 1. Backup database
log "Backing up database..."
mysqldump -u root -p tarlprathom > "$BACKUP_PATH/tarlprathom_$(date +%Y%m%d_%H%M%S).sql"

# 2. Enable maintenance mode
cd $APP_PATH
php artisan down --message="System maintenance in progress. Please check back in 5 minutes." --retry=300

# 3. Pull latest code
log "Pulling latest code..."
git pull origin main

# 4. Install dependencies
log "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

# 5. Run migrations
log "Running migrations..."
php artisan migrate --force

# 6. Clear caches
log "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 7. Build assets
log "Building assets..."
npm install --production
npm run build

# 8. Optimize
log "Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9. Restart services
log "Restarting services..."
sudo supervisorctl restart all

# 10. Disable maintenance mode
php artisan up

log "Deployment completed successfully!"

# 11. Run post-deployment tests
log "Running post-deployment tests..."
php artisan test --testsuite=Deployment
```

### C. Rollback Script

Create `rollback.sh`:

```bash
#!/bin/bash

echo "EMERGENCY ROLLBACK PROCEDURE"
echo "==========================="

# Get previous commit
PREVIOUS_COMMIT=$(git rev-parse HEAD~1)

echo "Rolling back to commit: $PREVIOUS_COMMIT"
echo "Continue? (yes/no)"
read CONFIRM

if [ "$CONFIRM" != "yes" ]; then
    echo "Rollback cancelled."
    exit 0
fi

# 1. Enable maintenance mode
php artisan down

# 2. Rollback code
git reset --hard $PREVIOUS_COMMIT

# 3. Rollback database (if needed)
echo "Rollback database? (yes/no)"
read DB_ROLLBACK

if [ "$DB_ROLLBACK" == "yes" ]; then
    php artisan migrate:rollback --step=1
fi

# 4. Clear caches
php artisan cache:clear
php artisan config:clear

# 5. Rebuild assets
npm run build

# 6. Restart services
sudo supervisorctl restart all

# 7. Disable maintenance mode
php artisan up

echo "Rollback completed!"
```

## 4. Continuous Integration Setup

### GitHub Actions Workflow

Create `.github/workflows/tests.yml`:

```yaml
name: Tests

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: tarlprathom_test
          MYSQL_ROOT_PASSWORD: password
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.1'
        extensions: mbstring, pdo, pdo_mysql
        coverage: xdebug

    - name: Copy .env
      run: php -r "file_exists('.env') || copy('.env.example', '.env');"
    
    - name: Install Dependencies
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
    
    - name: Generate key
      run: php artisan key:generate
    
    - name: Directory Permissions
      run: chmod -R 777 storage bootstrap/cache
    
    - name: Run Migrations
      env:
        DB_CONNECTION: mysql
        DB_HOST: 127.0.0.1
        DB_PORT: 3306
        DB_DATABASE: tarlprathom_test
        DB_USERNAME: root
        DB_PASSWORD: password
      run: php artisan migrate
    
    - name: Run Tests
      env:
        DB_CONNECTION: mysql
        DB_HOST: 127.0.0.1
        DB_PORT: 3306
        DB_DATABASE: tarlprathom_test
        DB_USERNAME: root
        DB_PASSWORD: password
      run: php artisan test --coverage
    
    - name: Run PHPStan
      run: ./vendor/bin/phpstan analyse
    
    - name: Run Pint
      run: ./vendor/bin/pint --test
```

## 5. Monitoring & Alerts

### Health Check Endpoint

Create `app/Http/Controllers/HealthController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HealthController extends Controller
{
    public function check()
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache' => $this->checkCache(),
            'storage' => $this->checkStorage(),
            'queue' => $this->checkQueue(),
        ];

        $healthy = !in_array(false, $checks);
        
        return response()->json([
            'status' => $healthy ? 'healthy' : 'unhealthy',
            'checks' => $checks,
            'timestamp' => now()->toIso8601String()
        ], $healthy ? 200 : 503);
    }

    private function checkDatabase()
    {
        try {
            DB::select('SELECT 1');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkCache()
    {
        try {
            Cache::put('health_check', true, 1);
            return Cache::get('health_check') === true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function checkStorage()
    {
        return is_writable(storage_path());
    }

    private function checkQueue()
    {
        // Check if queue is processing
        // Implementation depends on your queue setup
        return true;
    }
}
```

Add route:
```php
Route::get('/health', [HealthController::class, 'check']);
```

## 6. Performance Testing

### Load Testing Script

Create `tests/load-test.js`:

```javascript
import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    stages: [
        { duration: '30s', target: 20 },  // Ramp up
        { duration: '1m', target: 20 },   // Stay at 20 users
        { duration: '30s', target: 0 },   // Ramp down
    ],
    thresholds: {
        http_req_duration: ['p(95)<500'], // 95% of requests under 500ms
    },
};

const BASE_URL = 'http://localhost:8000';

export default function () {
    // Test homepage
    let res = http.get(BASE_URL);
    check(res, {
        'homepage status is 200': (r) => r.status === 200,
        'homepage loads quickly': (r) => r.timings.duration < 500,
    });

    sleep(1);

    // Test AJAX endpoint
    res = http.get(`${BASE_URL}/api/assessment-data?subject=khmer`);
    check(res, {
        'API status is 200': (r) => r.status === 200,
        'API returns JSON': (r) => r.headers['Content-Type'].includes('application/json'),
    });

    sleep(1);
}
```

Run with: `k6 run tests/load-test.js`