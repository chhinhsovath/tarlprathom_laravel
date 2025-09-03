# TaRL System Test Accounts

## Admin Accounts
- **Email**: admin@tarl.edu.kh
  **Password**: admin123
  **Role**: System Administrator
  **Access**: Full system access

- **Email**: kairav@prathaminternational.org
  **Password**: admin123
  **Role**: Admin
  **Access**: Full system access

## Coordinator Accounts
- **Email**: battambang.coordinator@tarl.edu.kh
  **Password**: coord123
  **Role**: Provincial Coordinator - Battambang
  **Access**: Data import, language management, provincial oversight

- **Email**: kampongcham.coordinator@tarl.edu.kh
  **Password**: coord123
  **Role**: Provincial Coordinator - Kampongcham
  **Access**: Data import, language management, provincial oversight

## Mentor Accounts

### Battambang Province
- **Email**: battambang.mentor1@tarl.edu.kh
  **Password**: mentor123
  **Schools**: 5 schools assigned
  
- **Email**: battambang.mentor2@tarl.edu.kh
  **Password**: mentor123
  **Schools**: 5 schools assigned
  
- **Email**: battambang.mentor3@tarl.edu.kh
  **Password**: mentor123
  **Schools**: 5 schools assigned

### Kampongcham Province
- **Email**: kampongcham.mentor1@tarl.edu.kh
  **Password**: mentor123
  **Schools**: 5 schools assigned
  
- **Email**: kampongcham.mentor2@tarl.edu.kh
  **Password**: mentor123
  **Schools**: 5 schools assigned
  
- **Email**: kampongcham.mentor3@tarl.edu.kh
  **Password**: mentor123
  **Schools**: 5 schools assigned

## Teacher Accounts
Each school has 3-5 teachers. Sample accounts:

- **Email**: school31.teacher1@tarl.edu.kh
  **Password**: teacher123
  **School**: School ID 31
  
- **Email**: school32.teacher1@tarl.edu.kh
  **Password**: teacher123
  **School**: School ID 32

## Features by Role

### Admin
- Full dashboard with all statistics
- User management (CRUD for all users)
- School management (CRUD for all schools)
- Student management (CRUD for all students)
- Assessment management
- Mentoring visit reports
- Settings and configuration
- Language/translation management

### Coordinator
- Provincial dashboard
- Data import center (schools, teachers, students)
- Language center (switch languages, manage translations)
- View provincial statistics
- Generate reports
- Manage users within province

### Mentor
- School oversight dashboard
- Create mentoring visit reports
- View assigned schools' data
- Student assessments
- Teacher performance tracking
- Generate school reports

### Teacher
- Class dashboard
- Student roster management
- Enter assessment scores
- Track student progress
- View teaching materials
- Record teaching activities

## Data Overview

### Schools
- **Total**: 30 pilot schools
- **Provinces**: Battambang (15), Kampongcham (15)
- **Districts**: Rattanakamondol, Kampeas

### Sample Data Created
- **Students**: 30-50 per school (900-1500 total)
- **Assessments**: Baseline, Midline, Endline for 70% of students
- **Mentoring Visits**: 3-5 per school
- **Teaching Activities**: 5-10 per teacher
- **Learning Materials**: 8 types available
- **Progress Tracking**: Monthly records for sample students

## Testing Workflow

1. **Admin Login**: Full system overview, manage all entities
2. **Coordinator Login**: Import data, manage translations, provincial oversight
3. **Mentor Login**: School visits, assessments, teacher support
4. **Teacher Login**: Classroom management, student progress

## Language Support
- **Khmer (km)**: Default language
- **English (en)**: Available
- Switch language from user menu or coordinator's language center

## Important URLs
- Dashboard: `/dashboard`
- Schools: `/schools`
- Students: `/students`
- Assessments: `/assessments`
- Reports: `/reports`
- Coordinator Workspace: `/coordinator`
- Settings: `/settings`