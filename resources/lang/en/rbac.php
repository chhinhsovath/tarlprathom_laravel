<?php

return [
    // Main Navigation
    'Role-Based Access Control' => 'Role-Based Access Control',
    'RBAC Dashboard' => 'RBAC Dashboard',
    'User Management' => 'User Management',
    'Data Access Control' => 'Data Access Control',

    // Dashboard
    'System Overview' => 'System Overview',
    'User Statistics' => 'User Statistics',
    'Role Distribution' => 'Role Distribution',
    'Data Access Matrix' => 'Data Access Matrix',
    'Recent Activities' => 'Recent Activities',

    // Users
    'Users' => 'Users',
    'All Users' => 'All Users',
    'Active Users' => 'Active Users',
    'Inactive Users' => 'Inactive Users',
    'Create User' => 'Create User',
    'Edit User' => 'Edit User',
    'View User' => 'View User',
    'Delete User' => 'Delete User',

    // Roles
    'Role' => 'Role',
    'Roles' => 'Roles',
    'Admin' => 'Admin',
    'Coordinator' => 'Coordinator',
    'Mentor' => 'Mentor',
    'Teacher' => 'Teacher',
    'Viewer' => 'Viewer',

    // User Fields
    'Name' => 'Name',
    'Email' => 'Email',
    'Password' => 'Password',
    'Confirm Password' => 'Confirm Password',
    'School' => 'School',
    'Province' => 'Province',
    'District' => 'District',
    'Commune' => 'Commune',
    'Village' => 'Village',
    'Phone' => 'Phone',
    'Gender' => 'Gender',
    'Male' => 'Male',
    'Female' => 'Female',
    'Status' => 'Status',
    'Active' => 'Active',
    'Inactive' => 'Inactive',
    'Holding Classes' => 'Holding Classes',
    'Assigned Subject' => 'Assigned Subject',
    'Assigned Schools' => 'Assigned Schools',

    // Actions
    'Create' => 'Create',
    'Edit' => 'Edit',
    'Update' => 'Update',
    'Delete' => 'Delete',
    'View' => 'View',
    'Save' => 'Save',
    'Cancel' => 'Cancel',
    'Back' => 'Back',
    'Search' => 'Search',
    'Filter' => 'Filter',
    'Export' => 'Export',
    'Reset' => 'Reset',
    'Toggle Status' => 'Toggle Status',
    'Activate' => 'Activate',
    'Deactivate' => 'Deactivate',

    // Messages
    'User created successfully with :role role.' => 'User created successfully with :role role.',
    'User updated successfully.' => 'User updated successfully.',
    'User deleted successfully.' => 'User deleted successfully.',
    'User deactivated successfully.' => 'User deactivated successfully.',
    'User :status successfully.' => 'User :status successfully.',
    'Cannot delete the last admin user.' => 'Cannot delete the last admin user.',
    'Access denied. Admin privileges required.' => 'Access denied. Admin privileges required.',
    'activated' => 'activated',
    'deactivated' => 'deactivated',

    // Search and Filters
    'Search users...' => 'Search users...',
    'All Roles' => 'All Roles',
    'All Schools' => 'All Schools',
    'All Statuses' => 'All Statuses',
    'Filter by role' => 'Filter by role',
    'Filter by school' => 'Filter by school',
    'Filter by status' => 'Filter by status',

    // Permissions
    'Permissions' => 'Permissions',
    'Data Access' => 'Data Access',
    'Can Create' => 'Can Create',
    'Can Read' => 'Can Read',
    'Can Update' => 'Can Update',
    'Can Delete' => 'Can Delete',
    'Full Access' => 'Full Access',
    'Limited Access' => 'Limited Access',
    'No Access' => 'No Access',

    // Data Isolation Levels
    'Full access to all data across all schools and users' => 'Full access to all data across all schools and users',
    'Access to all schools and students, limited user management' => 'Access to all schools and students, limited user management',
    'Access only to assigned schools, students, and teachers' => 'Access only to assigned schools, students, and teachers',
    'Access only to own school and assigned students' => 'Access only to own school and assigned students',
    'Read-only access to basic data, no modifications allowed' => 'Read-only access to basic data, no modifications allowed',
    'Unknown role' => 'Unknown role',

    // Statistics
    'Total Users' => 'Total Users',
    'Total Schools' => 'Total Schools',
    'Total Students' => 'Total Students',
    'Total Assessments' => 'Total Assessments',
    'Total Mentoring Visits' => 'Total Mentoring Visits',
    'Accessible Schools' => 'Accessible Schools',
    'Accessible Students' => 'Accessible Students',
    'Own Students' => 'Own Students',
    'Mentoring Visits Given' => 'Mentoring Visits Given',
    'Mentoring Visits Received' => 'Mentoring Visits Received',
    'Assessments Conducted' => 'Assessments Conducted',

    // Activities
    'Activities' => 'Activities',
    'Recent Activity' => 'Recent Activity',
    'No activities found' => 'No activities found',
    'Conducted mentoring visit at :school' => 'Conducted mentoring visit at :school',
    'Conducted assessment for :student' => 'Conducted assessment for :student',

    // Data Access Control
    'Schools Access' => 'Schools Access',
    'Students Access' => 'Students Access',
    'Teachers Access' => 'Teachers Access',
    'Data Isolation Level' => 'Data Isolation Level',
    'Access Control Matrix' => 'Access Control Matrix',
    'Role-Based Data Access' => 'Role-Based Data Access',

    // Validation Messages
    'The name field is required.' => 'The name field is required.',
    'The name may only contain letters, numbers, spaces, hyphens, apostrophes, and dots.' => 'The name may only contain letters, numbers, spaces, hyphens, apostrophes, and dots.',
    'The email field is required.' => 'The email field is required.',
    'The email must be a valid email address.' => 'The email must be a valid email address.',
    'The email has already been taken.' => 'The email has already been taken.',
    'The password field is required.' => 'The password field is required.',
    'The password must be at least 8 characters.' => 'The password must be at least 8 characters.',
    'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.' => 'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.',
    'The role field is required.' => 'The role field is required.',
    'The selected role is invalid.' => 'The selected role is invalid.',
    'The school field is required for teachers.' => 'The school field is required for teachers.',
    'The selected school is invalid.' => 'The selected school is invalid.',
    'The phone number format is invalid.' => 'The phone number format is invalid.',
    'The selected gender is invalid.' => 'The selected gender is invalid.',
    'At least one school must be assigned to mentors.' => 'At least one school must be assigned to mentors.',
    'One or more selected schools are invalid.' => 'One or more selected schools are invalid.',
    'Cannot change role: You are the last active admin.' => 'Cannot change role: You are the last active admin.',
    'Mentors must be assigned to at least one school.' => 'Mentors must be assigned to at least one school.',
    'Teachers must be assigned to a school.' => 'Teachers must be assigned to a school.',

    // Table Headers
    'User Info' => 'User Info',
    'Contact Info' => 'Contact Info',
    'Role & School' => 'Role & School',
    'Last Activity' => 'Last Activity',
    'Created Date' => 'Created Date',

    // Forms
    'User Information' => 'User Information',
    'Role Assignment' => 'Role Assignment',
    'School Assignment' => 'School Assignment',
    'Contact Information' => 'Contact Information',
    'Additional Information' => 'Additional Information',
    'Password must be at least 8 characters and include uppercase, lowercase, number, and special character' => 'Password must be at least 8 characters and include uppercase, lowercase, number, and special character',
    'Select the primary school for this teacher' => 'Select the primary school for this teacher',
    'Select multiple schools for this mentor' => 'Select multiple schools for this mentor',
    'Leave blank to keep current password' => 'Leave blank to keep current password',

    // Confirmations
    'Are you sure you want to delete this user?' => 'Are you sure you want to delete this user?',
    'Are you sure you want to deactivate this user?' => 'Are you sure you want to deactivate this user?',
    'Are you sure you want to activate this user?' => 'Are you sure you want to activate this user?',
    'This action cannot be undone.' => 'This action cannot be undone.',
    'This will remove the user\'s access to the system.' => 'This will remove the user\'s access to the system.',

    // Data Access Descriptions
    'Admin Role Description' => 'Full system administrator with complete access to all data, users, and system configurations.',
    'Coordinator Role Description' => 'Regional coordinator with access to all schools and students in their area, limited user management.',
    'Mentor Role Description' => 'School mentor with access to assigned schools, their students, and teachers for mentoring purposes.',
    'Teacher Role Description' => 'Classroom teacher with access to their own school and assigned students for teaching and assessment.',
    'Viewer Role Description' => 'Read-only access to basic system data for reporting and monitoring purposes.',

    // Navigation
    'Dashboard' => 'Dashboard',
    'Back to Dashboard' => 'Back to Dashboard',
    'Back to Users' => 'Back to Users',
    'User Profile' => 'User Profile',
    'System Settings' => 'System Settings',
];