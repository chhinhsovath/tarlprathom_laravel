# User Interaction Scenarios - Tarlprathom Laravel

## 1. Student Management Module

### 1.1 View Students List
**Flow:**
1. User navigates to `/students`
2. System displays paginated list with filters
3. User can search by name, filter by school/grade/gender
4. User can export to Excel

**AJAX Interactions:**
- None (server-side rendering)

**Validations:**
- User must be authenticated
- Teachers can only see their school's students

### 1.2 Add New Student
**Flow:**
1. Click "Add New Student" button
2. Fill form with student details
3. Submit form
4. System validates and saves

**AJAX Interactions:**
- None (traditional form submission)

**Validations:**
- Name: required, string, max:255
- Grade: required, integer, 1-6
- Gender: required, in:male,female
- School: required, exists in schools table
- Date of birth: optional, date format

### 1.3 Edit Student
**Flow:**
1. Click "Edit" on student row
2. Update form fields
3. Submit changes
4. System validates and updates

**Validations:**
- Same as Add New Student
- Teachers can only edit their school's students

### 1.4 Delete Student
**Flow:**
1. Click "Delete" on student row
2. Confirm deletion in alert dialog
3. System deletes student

**Validations:**
- User must have delete permission
- Cascade delete related assessments

## 2. Assessment Module

### 2.1 View Assessments
**Flow:**
1. Navigate to `/assessments`
2. View filtered assessment list
3. Search by student name
4. Filter by subject/cycle
5. Export to Excel

**AJAX Interactions:**
- GET `/api/assessment-data` for chart data (public page)

### 2.2 Create Assessment (Bulk Entry)
**Flow:**
1. Click "New Assessment"
2. Select subject and cycle
3. System loads eligible students
4. Enter assessment levels for each student
5. Submit all assessments

**AJAX Interactions:**
- GET `/assessments/create?subject=X&cycle=Y` (loads existing data)
- POST `/api/assessments/save-student` (saves individual assessment)
- POST `/api/assessments/submit-all` (finalizes submission)

**Validations:**
- Subject: required, in:khmer,math
- Cycle: required, in:baseline,midline,endline
- Level: required, depends on subject
- Student eligibility based on baseline scores

### 2.3 Public Assessment Results
**Flow:**
1. Visit homepage `/`
2. View charts by subject
3. Toggle between Khmer/Math

**AJAX Interactions:**
- GET `/api/assessment-data?subject=X` (loads chart data)

## 3. Mentoring Visits Module

### 3.1 View Mentoring Visits
**Flow:**
1. Navigate to `/mentoring`
2. Search by teacher/mentor/school/notes
3. Filter by date range
4. Export to Excel

**Validations:**
- Teachers see only their visits
- Mentors see only their visits

### 3.2 Log New Visit
**Flow:**
1. Click "Log Visit"
2. Select school, teacher, date
3. Enter observation and action plan
4. Upload photo (optional)
5. Submit

**Validations:**
- School: required
- Teacher: required, must belong to selected school
- Visit date: required
- Score: required, 0-100
- Observation: required, text
- Action plan: required, text

## 4. Reports Module

### 4.1 View Available Reports
**Flow:**
1. Navigate to `/reports`
2. View stats dashboard
3. Select report type based on role

### 4.2 Generate Reports
**Flow:**
1. Select report type
2. Apply filters
3. View results
4. Export to CSV

**Available Reports by Role:**
- **Admin/Viewer:** All reports
- **Teacher:** My Students, Class Progress
- **Mentor:** My Mentoring, School Visits

## 5. Language Switching

### 5.1 Change Language
**Flow:**
1. Click language switcher
2. Select English or Khmer
3. Page reloads with new language

**Technical Details:**
- GET `/language/{locale}`
- Sets session and cookie
- Persists across sessions

## 6. Authentication Flows

### 6.1 Login
**Flow:**
1. Enter email and password
2. Submit login form
3. Redirect to dashboard

### 6.2 Profile Management
**Flow:**
1. Navigate to `/profile`
2. Update name, email, password
3. Save changes

## Data Flow Architecture

```
User Interface (Bootstrap + jQuery)
        ↓
    AJAX Calls
        ↓
Laravel Routes (web.php)
        ↓
Controllers + Form Requests
        ↓
Eloquent Models
        ↓
MySQL Database
```

## Error Handling Patterns

1. **Client-side validation** (jQuery)
   - Immediate feedback
   - Prevent unnecessary server calls

2. **Server-side validation** (Laravel)
   - Security layer
   - Data integrity
   - Returns 422 with validation errors

3. **Error Display**
   - Form errors: inline below fields
   - General errors: alert boxes
   - Success messages: green notification bars