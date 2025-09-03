<?php

namespace Tests\Feature;

use App\Models\PilotSchool;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RbacSystemTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test schools
        $this->school1 = PilotSchool::create([
            'school_code' => 'TEST001',
            'school_name' => 'Test School 1',
            'province' => 'Test Province',
            'district' => 'Test District',
            'cluster' => 'Test Cluster',
        ]);
        
        $this->school2 = PilotSchool::create([
            'school_code' => 'TEST002',
            'school_name' => 'Test School 2',
            'province' => 'Test Province 2',
            'district' => 'Test District 2',
            'cluster' => 'Test Cluster 2',
        ]);

        // Create test users with different roles
        $this->admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->coordinator = User::create([
            'name' => 'Test Coordinator',
            'email' => 'coordinator@test.com',
            'password' => Hash::make('password'),
            'role' => 'coordinator',
            'is_active' => true,
        ]);

        $this->mentor = User::create([
            'name' => 'Test Mentor',
            'email' => 'mentor@test.com',
            'password' => Hash::make('password'),
            'role' => 'mentor',
            'is_active' => true,
        ]);

        $this->teacher = User::create([
            'name' => 'Test Teacher',
            'email' => 'teacher@test.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'school_id' => $this->school1->id,
            'is_active' => true,
        ]);

        $this->viewer = User::create([
            'name' => 'Test Viewer',
            'email' => 'viewer@test.com',
            'password' => Hash::make('password'),
            'role' => 'viewer',
            'is_active' => true,
        ]);

        // Assign schools to mentor
        $this->mentor->assignedPilotSchools()->attach([$this->school1->id, $this->school2->id]);

        // Create test students
        $this->student1 = Student::create([
            'name' => 'Test Student 1',
            'sex' => 'M',
            'school_id' => $this->school1->id,
            'teacher_id' => $this->teacher->id,
        ]);

        $this->student2 = Student::create([
            'name' => 'Test Student 2',
            'sex' => 'F',
            'school_id' => $this->school2->id,
        ]);
    }

    public function test_rbac_routes_require_authentication()
    {
        // Test that RBAC routes require authentication
        $response = $this->get('/rbac');
        $response->assertRedirect('/login');

        $response = $this->get('/rbac/users');
        $response->assertRedirect('/login');

        $response = $this->get('/role-based-access-control');
        $response->assertRedirect('/login');
    }

    public function test_rbac_routes_require_admin_role()
    {
        // Test that non-admin users cannot access RBAC
        $this->actingAs($this->teacher);
        
        $response = $this->get('/rbac');
        $response->assertStatus(403);

        $response = $this->get('/rbac/users');
        $response->assertStatus(403);

        $this->actingAs($this->mentor);
        
        $response = $this->get('/rbac');
        $response->assertStatus(403);

        $this->actingAs($this->viewer);
        
        $response = $this->get('/rbac');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_rbac_dashboard()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get('/rbac');
        $response->assertStatus(200);
        $response->assertViewIs('rbac.index');
        $response->assertViewHas(['statistics', 'recentActivities', 'rolePermissions']);
    }

    public function test_admin_can_access_user_management()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get('/rbac/users');
        $response->assertStatus(200);
        $response->assertViewIs('rbac.users');
        $response->assertViewHas(['users', 'schools', 'roles']);
    }

    public function test_admin_can_view_user_details()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get("/rbac/users/{$this->teacher->id}");
        $response->assertStatus(200);
        $response->assertViewIs('rbac.show');
        $response->assertViewHas(['user', 'dataStats', 'activities']);
    }

    public function test_admin_can_create_new_user()
    {
        $this->actingAs($this->admin);
        
        // Test create form
        $response = $this->get('/rbac/users/create');
        $response->assertStatus(200);
        $response->assertViewIs('rbac.create');
        
        // Test user creation
        $userData = [
            'name' => 'New Test User',
            'email' => 'newuser@test.com',
            'password' => 'NewPassword123!',
            'role' => 'teacher',
            'school_id' => $this->school1->id,
            'is_active' => true,
        ];

        $response = $this->post('/rbac/users', $userData);
        $response->assertRedirect('/rbac/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@test.com',
            'role' => 'teacher',
        ]);
    }

    public function test_data_isolation_for_different_roles()
    {
        // Test admin can access all schools
        $adminSchoolIds = $this->admin->getAccessibleSchoolIds();
        $this->assertCount(2, $adminSchoolIds);
        $this->assertContains($this->school1->id, $adminSchoolIds);
        $this->assertContains($this->school2->id, $adminSchoolIds);

        // Test coordinator can access all schools
        $coordinatorSchoolIds = $this->coordinator->getAccessibleSchoolIds();
        $this->assertCount(2, $coordinatorSchoolIds);

        // Test mentor can access only assigned schools
        $mentorSchoolIds = $this->mentor->getAccessibleSchoolIds();
        $this->assertCount(2, $mentorSchoolIds);
        $this->assertContains($this->school1->id, $mentorSchoolIds);
        $this->assertContains($this->school2->id, $mentorSchoolIds);

        // Test teacher can access only their school
        $teacherSchoolIds = $this->teacher->getAccessibleSchoolIds();
        $this->assertCount(1, $teacherSchoolIds);
        $this->assertContains($this->school1->id, $teacherSchoolIds);

        // Test viewer has no school access by default
        $viewerSchoolIds = $this->viewer->getAccessibleSchoolIds();
        $this->assertCount(0, $viewerSchoolIds);
    }

    public function test_student_data_access_by_role()
    {
        // Admin can access all students
        $adminStudents = $this->admin->getAccessibleStudents()->count();
        $this->assertEquals(2, $adminStudents);

        // Coordinator can access all students
        $coordinatorStudents = $this->coordinator->getAccessibleStudents()->count();
        $this->assertEquals(2, $coordinatorStudents);

        // Mentor can access students from assigned schools
        $mentorStudents = $this->mentor->getAccessibleStudents()->count();
        $this->assertEquals(2, $mentorStudents);

        // Teacher can access students from their school only
        $teacherStudents = $this->teacher->getAccessibleStudents()->count();
        $this->assertEquals(1, $teacherStudents);

        // Viewer has no student access by default
        $viewerStudents = $this->viewer->getAccessibleStudents()->count();
        $this->assertEquals(0, $viewerStudents);
    }

    public function test_role_helper_methods()
    {
        $this->assertTrue($this->admin->isAdmin());
        $this->assertFalse($this->admin->isTeacher());

        $this->assertTrue($this->coordinator->isCoordinator());
        $this->assertFalse($this->coordinator->isAdmin());

        $this->assertTrue($this->mentor->isMentor());
        $this->assertFalse($this->mentor->isTeacher());

        $this->assertTrue($this->teacher->isTeacher());
        $this->assertFalse($this->teacher->isMentor());

        $this->assertTrue($this->viewer->isViewer());
        $this->assertFalse($this->viewer->isAdmin());
    }

    public function test_user_can_access_specific_school()
    {
        // Admin can access any school
        $this->assertTrue($this->admin->canAccessSchool($this->school1->id));
        $this->assertTrue($this->admin->canAccessSchool($this->school2->id));

        // Teacher can access only their school
        $this->assertTrue($this->teacher->canAccessSchool($this->school1->id));
        $this->assertFalse($this->teacher->canAccessSchool($this->school2->id));

        // Mentor can access assigned schools
        $this->assertTrue($this->mentor->canAccessSchool($this->school1->id));
        $this->assertTrue($this->mentor->canAccessSchool($this->school2->id));
    }

    public function test_user_can_access_specific_student()
    {
        // Admin can access any student
        $this->assertTrue($this->admin->canAccessStudent($this->student1->id));
        $this->assertTrue($this->admin->canAccessStudent($this->student2->id));

        // Teacher can access students from their school
        $this->assertTrue($this->teacher->canAccessStudent($this->student1->id));
        $this->assertFalse($this->teacher->canAccessStudent($this->student2->id));

        // Mentor can access students from assigned schools
        $this->assertTrue($this->mentor->canAccessStudent($this->student1->id));
        $this->assertTrue($this->mentor->canAccessStudent($this->student2->id));
    }

    public function test_legacy_rbac_route_redirects()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get('/role-based-access-control');
        $response->assertRedirect('/rbac');
    }
}