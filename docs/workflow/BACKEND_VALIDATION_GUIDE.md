# Backend Validation & Business Logic Guide

## 1. Form Request Validation

### Current Implementation Status

#### StoreStudentRequest
```php
public function rules()
{
    return [
        'name' => 'required|string|max:255',
        'grade' => 'required|integer|min:1|max:6',
        'gender' => 'required|in:male,female',
        'date_of_birth' => 'nullable|date|before:today',
        'school_id' => 'required|exists:schools,id',
        'address' => 'nullable|string|max:500',
        'parent_name' => 'nullable|string|max:255',
        'contact_number' => 'nullable|string|max:20',
    ];
}
```

#### StoreAssessmentRequest
```php
public function rules()
{
    return [
        'student_id' => 'required|exists:students,id',
        'subject' => 'required|in:khmer,math',
        'cycle' => 'required|in:baseline,midline,endline',
        'level' => 'required|string',
        'score' => 'required|integer|min:0|max:100',
        'assessed_at' => 'required|date',
    ];
}

public function withValidator($validator)
{
    $validator->after(function ($validator) {
        if ($this->subject === 'khmer') {
            $validLevels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        } else {
            $validLevels = ['Beginner', '1-Digit', '2-Digit', 'Subtraction', 'Division', 'Word Problem'];
        }
        
        if (!in_array($this->level, $validLevels)) {
            $validator->errors()->add('level', 'Invalid level for selected subject.');
        }
    });
}
```

#### StoreMentoringVisitRequest
```php
public function rules()
{
    return [
        'school_id' => 'required|exists:schools,id',
        'teacher_id' => 'required|exists:users,id',
        'visit_date' => 'required|date|before_or_equal:today',
        'score' => 'required|integer|min:0|max:100',
        'observation' => 'required|string|min:10',
        'action_plan' => 'required|string|min:10',
        'follow_up_required' => 'boolean',
        'photo' => 'nullable|image|max:5120', // 5MB max
    ];
}

public function withValidator($validator)
{
    $validator->after(function ($validator) {
        // Verify teacher belongs to selected school
        $teacher = User::find($this->teacher_id);
        if ($teacher && $teacher->school_id !== $this->school_id) {
            $validator->errors()->add('teacher_id', 'Selected teacher does not belong to the selected school.');
        }
    });
}
```

## 2. Business Logic Validation

### Assessment Eligibility Rules

```php
// In AssessmentController@create
private function getEligibleStudents($subject, $cycle, $schoolId = null)
{
    if ($cycle === 'baseline') {
        // All students are eligible for baseline
        return Student::when($schoolId, function($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })->get();
    }
    
    // For midline/endline, check baseline performance
    $eligibleLevels = $subject === 'khmer' 
        ? ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story']
        : ['Beginner', '1-Digit', '2-Digit', 'Subtraction'];
    
    return Student::whereHas('assessments', function($q) use ($subject, $eligibleLevels) {
        $q->where('subject', $subject)
          ->where('cycle', 'baseline')
          ->whereIn('level', $eligibleLevels);
    })
    ->when($schoolId, function($q) use ($schoolId) {
        $q->where('school_id', $schoolId);
    })
    ->get();
}
```

### Authorization Rules

```php
// In StudentPolicy
public function viewAny(User $user)
{
    return in_array($user->role, ['admin', 'teacher', 'mentor', 'viewer']);
}

public function view(User $user, Student $student)
{
    if ($user->isAdmin() || $user->isViewer() || $user->isMentor()) {
        return true;
    }
    
    if ($user->isTeacher()) {
        return $student->school_id === $user->school_id;
    }
    
    return false;
}

public function create(User $user)
{
    return in_array($user->role, ['admin', 'teacher']);
}

public function update(User $user, Student $student)
{
    if ($user->isAdmin()) {
        return true;
    }
    
    if ($user->isTeacher()) {
        return $student->school_id === $user->school_id;
    }
    
    return false;
}

public function delete(User $user, Student $student)
{
    return $user->isAdmin();
}
```

## 3. Data Integrity Rules

### Cascade Deletes
```php
// In migrations
$table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
$table->foreign('school_id')->references('id')->on('schools')->onDelete('restrict');
```

### Unique Constraints
```php
// Prevent duplicate assessments
$table->unique(['student_id', 'subject', 'cycle']);
```

### Business Rules Enforcement
```php
// In Model Observer
class AssessmentObserver
{
    public function creating(Assessment $assessment)
    {
        // Ensure assessment date is not in the future
        if ($assessment->assessed_at > now()) {
            throw new \Exception('Assessment date cannot be in the future');
        }
        
        // Ensure student has baseline before midline/endline
        if (in_array($assessment->cycle, ['midline', 'endline'])) {
            $hasBaseline = Assessment::where('student_id', $assessment->student_id)
                ->where('subject', $assessment->subject)
                ->where('cycle', 'baseline')
                ->exists();
                
            if (!$hasBaseline) {
                throw new \Exception('Student must have baseline assessment first');
            }
        }
    }
}
```

## 4. AJAX Validation Pattern

### Client-Side Validation
```javascript
function validateAssessmentData(data) {
    const errors = {};
    
    if (!data.student_id) {
        errors.student_id = 'Student is required';
    }
    
    if (!data.level) {
        errors.level = 'Level is required';
    }
    
    if (data.subject === 'khmer') {
        const validLevels = ['Beginner', 'Letter', 'Word', 'Paragraph', 'Story', 'Comp. 1', 'Comp. 2'];
        if (!validLevels.includes(data.level)) {
            errors.level = 'Invalid level for Khmer';
        }
    }
    
    return Object.keys(errors).length > 0 ? errors : null;
}
```

### Server-Side Double Validation
```php
// Always validate on server even if client validated
public function saveStudentAssessment(Request $request)
{
    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'subject' => 'required|in:khmer,math',
        'cycle' => 'required|in:baseline,midline,endline',
        'level' => 'required|string',
        'gender' => 'required|in:male,female'
    ]);
    
    // Additional business logic validation
    $this->validateLevelForSubject($validated['subject'], $validated['level']);
    $this->validateStudentEligibility($validated['student_id'], $validated['cycle']);
    
    // Process...
}
```

## 5. Security Validations

### Input Sanitization
```php
// In FormRequest
protected function prepareForValidation()
{
    $this->merge([
        'name' => strip_tags($this->name),
        'address' => strip_tags($this->address),
        'observation' => strip_tags($this->observation),
    ]);
}
```

### File Upload Validation
```php
public function rules()
{
    return [
        'photo' => [
            'nullable',
            'image',
            'mimes:jpeg,png,jpg',
            'max:5120', // 5MB
            'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'
        ]
    ];
}
```

### SQL Injection Prevention
```php
// Always use parameter binding
$students = DB::select('SELECT * FROM students WHERE school_id = ?', [$schoolId]);

// Or use Eloquent
$students = Student::where('school_id', $schoolId)->get();

// Never do this
$students = DB::select("SELECT * FROM students WHERE school_id = $schoolId"); // DANGEROUS!
```

## 6. Validation Error Messages

### Custom Messages
```php
public function messages()
{
    return [
        'name.required' => 'Student name is required.',
        'grade.between' => 'Grade must be between 1 and 6.',
        'school_id.exists' => 'Selected school does not exist.',
        'level.in' => 'Invalid level for the selected subject.',
    ];
}
```

### Localized Messages
```php
// In lang/km/validation.php
return [
    'required' => ':attribute ត្រូវបានទាមទារ។',
    'email' => ':attribute ត្រូវតែជាអាសយដ្ឋានអ៊ីមែលត្រឹមត្រូវ។',
    'attributes' => [
        'name' => 'ឈ្មោះ',
        'grade' => 'ថ្នាក់',
        'subject' => 'មុខវិជ្ជា',
    ],
];
```

## 7. Testing Validation Rules

```php
/** @test */
public function it_validates_student_creation()
{
    $response = $this->actingAs($this->admin)
        ->post('/students', [
            'name' => '', // Empty name
            'grade' => 7, // Invalid grade
            'gender' => 'other', // Invalid gender
        ]);
        
    $response->assertSessionHasErrors(['name', 'grade', 'gender']);
}

/** @test */
public function it_enforces_school_isolation_for_teachers()
{
    $otherSchool = School::factory()->create();
    $student = Student::factory()->create(['school_id' => $otherSchool->id]);
    
    $response = $this->actingAs($this->teacher)
        ->put("/students/{$student->id}", [
            'name' => 'Updated Name',
        ]);
        
    $response->assertForbidden();
}
```

## 8. Common Validation Patterns

### Date Range Validation
```php
$rules = [
    'from_date' => 'required|date',
    'to_date' => 'required|date|after_or_equal:from_date',
];
```

### Conditional Validation
```php
$rules = [
    'follow_up_required' => 'boolean',
    'follow_up_date' => 'required_if:follow_up_required,true|date|after:today',
];
```

### Array Validation
```php
$rules = [
    'assessments' => 'required|array',
    'assessments.*.student_id' => 'required|exists:students,id',
    'assessments.*.level' => 'required|string',
];
```

## 9. Validation Checklist

Before deployment, ensure:

- [ ] All user inputs are validated
- [ ] Business rules are enforced at model level
- [ ] File uploads are restricted by type and size
- [ ] AJAX endpoints have CSRF protection
- [ ] Error messages are user-friendly
- [ ] Validation works in both languages
- [ ] Authorization rules prevent data leaks
- [ ] Database constraints match validation rules
- [ ] No SQL injection vulnerabilities
- [ ] All forms have client + server validation