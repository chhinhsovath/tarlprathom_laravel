<?php

namespace Tests\Unit\Services;

use App\Models\MentoringVisit;
use App\Models\PilotSchool;
use App\Models\User;
use App\Services\MentoringVisitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MentoringVisitServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MentoringVisitService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new MentoringVisitService();
    }

    /** @test */
    public function test_validates_teacher_belongs_to_school(): void
    {
        // Create test data
        $school = PilotSchool::factory()->create();
        $teacher = User::factory()->create([
            'role' => 'teacher',
            'pilot_school_id' => $school->id
        ]);

        // Assert teacher belongs to school
        $this->assertTrue(
            $this->service->validateTeacherSchoolAssignment($teacher->id, $school->id)
        );
        
        // Assert teacher doesn't belong to different school
        $this->assertFalse(
            $this->service->validateTeacherSchoolAssignment($teacher->id, 999)
        );
    }

    /** @test */
    public function test_validates_mentor_school_access(): void
    {
        // Create admin user
        $admin = User::factory()->create(['role' => 'admin']);
        $school = PilotSchool::factory()->create();

        // Admin should have access to all schools
        $this->assertTrue(
            $this->service->validateMentorSchoolAccess($admin, $school->id)
        );
    }

    /** @test */
    public function test_processes_boolean_fields(): void
    {
        $data = [
            'class_in_session' => 'Yes',
            'full_session_observed' => 'No',
            'follow_up_required' => '1',
        ];

        $processed = $this->invokeMethod($this->service, 'processBooleanFields', [$data]);

        $this->assertTrue($processed['class_in_session']);
        $this->assertFalse($processed['full_session_observed']);
        $this->assertTrue($processed['follow_up_required']);
    }

    /** @test */
    public function test_caches_provinces_list(): void
    {
        Cache::forget('provinces_list');

        // First call should set cache
        $provinces1 = $this->invokeMethod($this->service, 'getCachedProvinces');
        
        // Second call should use cache
        $provinces2 = $this->invokeMethod($this->service, 'getCachedProvinces');

        $this->assertEquals($provinces1, $provinces2);
        $this->assertTrue(Cache::has('provinces_list'));
    }

    /**
     * Helper to invoke private methods
     */
    private function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
