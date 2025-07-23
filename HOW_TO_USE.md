# TaRL System - Complete User Guide

This guide contains all the features and usage instructions for the TaRL (Teaching at the Right Level) assessment system.

**Important Note**: This system is designed specifically for Grade 4 and Grade 5 students only.

## Table of Contents
1. [User Roles](#user-roles)
2. [Mentor-School Assignment](#mentor-school-assignment)
3. [Student Assessment Selection](#student-assessment-selection)
4. [Assessment Management](#assessment-management)
5. [Mentoring Visits](#mentoring-visits)
6. [Bulk Import Features](#bulk-import-features)
7. [Reporting](#reporting)
8. [Resource Management](#resource-management)

---

## User Roles

The system has four main user roles:
- **Administrator**: Full system access
- **Teacher**: Can manage their own students and conduct assessments
- **Mentor**: Can verify assessments and conduct mentoring visits in assigned schools
- **Viewer**: Read-only access to reports and data

---

## Mentor-School Assignment

### For Administrators

**Purpose**: Assign specific schools to mentors so they can only access and verify assessments in their assigned schools.

**How to use**:
1. Navigate to **Users** page
2. Click on a mentor user to view their profile
3. Click the **"Assign Schools"** button (only visible for mentor users)
4. Select the schools you want to assign to this mentor using checkboxes
5. Click **"Save School Assignments"**

**What happens**:
- Mentors will only see their assigned schools in dropdown menus
- Mentoring visits are restricted to assigned schools
- Mentors can only view assessments from their assigned schools

---

## Student Assessment Selection

### For Administrators and Teachers

**Purpose**: Select which students should participate in midline and endline assessments.

**How to use**:
1. Go to **Assessments** page
2. Click **"Select Students"** button
3. Choose between **Midline** or **Endline** tabs
4. Use filters to find students:
   - Search by name
   - Filter by school (Admin only)
   - Filter by grade (4 or 5)
5. Select students using checkboxes
6. Use **"Select All"** or **"Deselect All"** for bulk selection
7. Click **"Save Selection"**

**Important notes**:
- **Baseline assessments**: All students are automatically eligible (no selection needed)
- **Midline/Endline assessments**: Only selected students will appear in assessment lists
- Teachers can only select their own students
- Administrators can select students from any school

---

## Assessment Management

### Creating Assessments

**For Teachers and Mentors**:
1. Go to **Assessments** page
2. Click **"New Assessment"**
3. Select:
   - Subject (Khmer or Math)
   - Assessment Cycle (Baseline, Midline, or Endline)
4. Students will be displayed based on:
   - **Baseline**: All students
   - **Midline/Endline**: Only manually selected students (or those eligible based on baseline scores)
5. Enter assessment results for each student
6. Save assessments

### Assessment Cycles
- **Baseline**: Initial assessment for all students
- **Midline**: Mid-year assessment for selected students
- **Endline**: Final assessment for selected students

---

## Mentoring Visits

### For Mentors

**Purpose**: Document school visits and teacher support activities.

**How to use**:
1. Go to **Mentoring Log** page
2. Click **"Log Visit"** or **"New Visit"**
3. Fill in:
   - Visit date
   - School (only assigned schools will appear)
   - Teacher being mentored
   - Observation notes
   - Action plan
   - Follow-up requirements
4. Complete the questionnaire sections
5. Upload photos if needed
6. Submit the visit log

**Access restrictions**:
- Mentors only see schools assigned to them by administrators
- Cannot create visits for unassigned schools

### For Administrators

Administrators can:
- View all mentoring visits across all schools
- Edit any mentoring visit record
- Export mentoring visit data

---

## Bulk Import Features

### Importing Users

**For Administrators**:
1. Go to **Users** page
2. Click **"Import Users"**
3. Download the template CSV file
4. Fill in user data:
   - Name
   - Email
   - Password
   - Role (admin, teacher, mentor, viewer)
   - School
   - Phone number
5. Upload the completed CSV file
6. Review import results

### Importing Students

**For Administrators and Teachers**:
1. Go to **Students** page
2. Click **"Import Students"**
3. Download the template CSV file
4. Fill in student data:
   - Student ID
   - Name
   - Grade (4 or 5 only)
   - Gender
   - Date of birth
   - School
   - Class
5. Upload the completed CSV file
6. Review import results

### Importing Schools

**For Administrators**:
1. Go to **Schools** page
2. Click **"Import Schools"**
3. Download the template CSV file
4. Fill in school data:
   - School name
   - Province
   - District
   - Commune
5. Upload the completed CSV file
6. Review import results

---

## Reporting

### Available Reports

1. **Student Performance Report**
   - Shows individual student progress across assessment cycles
   - Accessible by: All roles

2. **Progress Tracking Report**
   - Tracks improvement from baseline to endline
   - Accessible by: All roles

3. **School Comparison Report**
   - Compares performance across schools
   - Accessible by: Administrators and Mentors

4. **Mentoring Impact Report**
   - Shows correlation between mentoring visits and student improvement
   - Accessible by: Administrators and Mentors

5. **My Mentoring Report**
   - Personal mentoring activity summary
   - Accessible by: Mentors

### Accessing Reports

1. Go to **Reports** page
2. Select the desired report type
3. Apply filters:
   - Date range
   - School
   - Grade
   - Subject
   - Assessment cycle
4. View or export results

---

## Resource Management

### For Administrators

**Purpose**: Share educational resources with teachers and mentors.

**How to use**:
1. Go to **Manage Resources** (Admin menu)
2. Click **"Add Resource"**
3. Fill in:
   - Title
   - Description
   - Type (Youtube, Excel, PDF)
   - Upload file or enter YouTube URL
   - Set visibility (Public/Private)
4. Save the resource

### For Teachers and Mentors

**Accessing resources**:
1. Go to **Resources** page
2. Browse available resources
3. Use filters to find specific types
4. Click to view or download

---

## Filter Issues and Solutions

### Empty Query Parameters

The system handles empty filter parameters correctly. URLs like:
```
/mentoring?search=&school_id=&from_date=&to_date=
```
Will show all results without applying filters.

---

## Language Support

The system supports:
- **Khmer** (default)
- **English**

To change language:
1. Click on the language selector in the navigation
2. Choose your preferred language

All interfaces, including buttons, messages, and reports, are available in both languages.

---

## Quick Tips

1. **For new mentors**: First get schools assigned by an administrator before starting any activities
2. **For assessment planning**: Select students for midline/endline before the assessment period begins
3. **Regular backups**: Export data regularly using the export features
4. **Filter usage**: Clear filters by submitting empty filter forms to see all data

---

## Common Workflows

### Setting up a new school year

1. **Administrator**:
   - Import or create schools
   - Import or create users (teachers and mentors)
   - Assign schools to mentors
   - Import students

2. **Teachers**:
   - Verify their student lists
   - Conduct baseline assessments for all students

3. **Administrator/Teachers**:
   - Select students for midline assessments
   - Later, select students for endline assessments

4. **Mentors**:
   - Visit assigned schools
   - Support teachers
   - Verify assessment quality
   - Document visits

### End of year process

1. Export all assessment data
2. Generate final reports
3. Archive the data
4. Prepare for next year

---

## Support

For technical issues or questions about using the system, contact your system administrator.

---

*Last updated: July 2025*