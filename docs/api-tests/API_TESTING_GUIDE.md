# API Testing Guide - Tarlprathom Laravel

## Testing Tools
- **Postman** for manual API testing
- **Laravel HTTP Tests** for automated testing
- **Browser DevTools** for AJAX debugging

## API Endpoints Testing Checklist

### 1. Student Management APIs

#### GET /students (Index)
```bash
curl -X GET http://localhost:8000/students \
  -H "Accept: application/json" \
  -H "Authorization: Bearer {token}"
```
**Test Cases:**
- ✅ Returns paginated list
- ✅ Filters work (school_id, grade, gender)
- ✅ Search works (name)
- ✅ Teachers see only their school

#### POST /students (Store)
```bash
curl -X POST http://localhost:8000/students \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{
    "name": "Test Student",
    "grade": 3,
    "gender": "male",
    "school_id": 1,
    "date_of_birth": "2015-01-01"
  }'
```
**Test Cases:**
- ✅ Valid data creates student
- ✅ Missing required fields return 422
- ✅ Invalid school_id rejected
- ✅ Teachers can only add to their school

#### PUT /students/{id} (Update)
```bash
curl -X PUT http://localhost:8000/students/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{
    "name": "Updated Name",
    "grade": 4
  }'
```
**Test Cases:**
- ✅ Valid update succeeds
- ✅ Non-existent student returns 404
- ✅ Unauthorized update returns 403
- ✅ Validation errors return 422

#### DELETE /students/{id} (Destroy)
```bash
curl -X DELETE http://localhost:8000/students/1 \
  -H "Authorization: Bearer {token}"
```
**Test Cases:**
- ✅ Authorized delete succeeds
- ✅ Unauthorized returns 403
- ✅ Non-existent returns 404

### 2. Assessment AJAX APIs

#### GET /api/assessment-data
```javascript
$.ajax({
    url: '/api/assessment-data',
    method: 'GET',
    data: { subject: 'khmer' },
    success: function(response) {
        console.log(response.chartData);
        console.log(response.cycleData);
    }
});
```
**Test Cases:**
- ✅ Returns chart data structure
- ✅ Filters by subject
- ✅ Public access (no auth required)

#### POST /api/assessments/save-student
```javascript
$.ajax({
    url: '/api/assessments/save-student',
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
        student_id: 1,
        subject: 'khmer',
        cycle: 'baseline',
        level: 'Word Level',
        gender: 'male'
    },
    success: function(response) {
        console.log(response.message);
    },
    error: function(xhr) {
        console.error(xhr.responseJSON);
    }
});
```
**Test Cases:**
- ✅ Valid assessment saves
- ✅ Invalid level for subject rejected
- ✅ Updates existing assessment
- ✅ Updates student gender if different

#### POST /api/assessments/submit-all
```javascript
$.ajax({
    url: '/api/assessments/submit-all',
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    data: {
        subject: 'math',
        cycle: 'midline',
        submitted_count: 25
    },
    success: function(response) {
        window.location.href = response.redirect;
    }
});
```
**Test Cases:**
- ✅ Submission logged
- ✅ Returns redirect URL
- ✅ Validates required fields

### 3. Export APIs

#### GET /students/export
```bash
curl -X GET "http://localhost:8000/students/export?grade=3&gender=male" \
  -H "Authorization: Bearer {token}" \
  -o students.xlsx
```
**Test Cases:**
- ✅ Returns Excel file
- ✅ Respects filters
- ✅ Teachers see only their data

#### GET /assessments/export
```bash
curl -X GET "http://localhost:8000/assessments/export?subject=khmer&cycle=baseline" \
  -H "Authorization: Bearer {token}" \
  -o assessments.xlsx
```

#### GET /mentoring/export
```bash
curl -X GET "http://localhost:8000/mentoring/export?from_date=2024-01-01" \
  -H "Authorization: Bearer {token}" \
  -o mentoring.xlsx
```

### 4. Dashboard APIs

#### GET /api/dashboard/stats
```javascript
$.get('/api/dashboard/stats', function(data) {
    updateStatCards(data);
});
```

#### GET /api/dashboard/overall-results
```javascript
$.get('/api/dashboard/overall-results', function(data) {
    updateOverallChart(data);
});
```

#### GET /api/dashboard/results-by-school
```javascript
$.get('/api/dashboard/results-by-school', { subject: 'math' }, function(data) {
    updateSchoolChart(data);
});
```

## Laravel HTTP Test Examples

Create test file: `tests/Feature/StudentApiTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StudentApiTest extends TestCase
{
    use RefreshDatabase;

    protected $teacher;
    protected $school;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->school = School::factory()->create();
        $this->teacher = User::factory()->create([
            'role' => 'teacher',
            'school_id' => $this->school->id
        ]);
    }

    public function test_teacher_can_only_see_their_school_students()
    {
        // Create students in teacher's school
        $ownStudents = Student::factory()->count(3)->create([
            'school_id' => $this->school->id
        ]);

        // Create students in another school
        $otherSchool = School::factory()->create();
        $otherStudents = Student::factory()->count(2)->create([
            'school_id' => $otherSchool->id
        ]);

        $response = $this->actingAs($this->teacher)
            ->get('/students');

        $response->assertStatus(200);
        
        // Should see own students
        foreach ($ownStudents as $student) {
            $response->assertSee($student->name);
        }
        
        // Should not see other school students
        foreach ($otherStudents as $student) {
            $response->assertDontSee($student->name);
        }
    }

    public function test_create_student_validation()
    {
        $response = $this->actingAs($this->teacher)
            ->post('/students', [
                'name' => '', // Missing required field
                'grade' => 7, // Invalid grade
                'gender' => 'other' // Invalid gender
            ]);

        $response->assertSessionHasErrors(['name', 'grade', 'gender']);
    }

    public function test_ajax_assessment_save()
    {
        $student = Student::factory()->create([
            'school_id' => $this->school->id
        ]);

        $response = $this->actingAs($this->teacher)
            ->postJson('/api/assessments/save-student', [
                'student_id' => $student->id,
                'subject' => 'khmer',
                'cycle' => 'baseline',
                'level' => 'Word Level',
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
            'level' => 'Word Level'
        ]);
    }
}
```

## AJAX Error Handling Pattern

```javascript
// Standard AJAX error handler
function handleAjaxError(xhr) {
    if (xhr.status === 422) {
        // Validation errors
        const errors = xhr.responseJSON.errors;
        $.each(errors, function(field, messages) {
            $('#' + field + '-error').text(messages[0]);
        });
    } else if (xhr.status === 403) {
        alert('You are not authorized to perform this action.');
    } else if (xhr.status === 404) {
        alert('Resource not found.');
    } else {
        alert('An error occurred. Please try again.');
    }
}

// Example usage
$.ajax({
    url: '/api/endpoint',
    method: 'POST',
    data: formData,
    success: function(response) {
        // Handle success
    },
    error: handleAjaxError
});
```

## Testing Checklist

Before deployment, ensure:

1. **Authentication Tests**
   - [ ] Login works
   - [ ] Logout clears session
   - [ ] Protected routes redirect to login

2. **Authorization Tests**
   - [ ] Teachers see only their school data
   - [ ] Mentors see only their visits
   - [ ] Admins see everything

3. **Validation Tests**
   - [ ] All required fields validated
   - [ ] Invalid data rejected with 422
   - [ ] CSRF protection active

4. **AJAX Tests**
   - [ ] All AJAX calls include CSRF token
   - [ ] Error responses handled gracefully
   - [ ] Loading states shown during requests

5. **Export Tests**
   - [ ] Excel exports contain correct data
   - [ ] Filters applied to exports
   - [ ] Large datasets handled efficiently