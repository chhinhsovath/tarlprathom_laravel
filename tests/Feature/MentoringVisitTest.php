<?php

namespace Tests\Feature;

use App\Models\MentoringVisit;
use App\Models\School;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MentoringVisitTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $mentor;
    protected $teacher;
    protected $school;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true
        ]);

        $this->mentor = User::factory()->create([
            'role' => 'mentor',
            'is_active' => true
        ]);

        $this->school = School::factory()->create();

        $this->teacher = User::factory()->create([
            'role' => 'teacher',
            'is_active' => true,
            'school_id' => $this->school->id
        ]);
    }

    /** @test */
    public function admin_can_view_mentoring_visits_index()
    {
        MentoringVisit::factory()->count(3)->create([
            'mentor_id' => $this->mentor->id,
            'teacher_id' => $this->teacher->id,
            'school_id' => $this->school->id
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('mentoring.index'));

        $response->assertStatus(200);
        $response->assertViewIs('mentoring.index');
        $response->assertViewHas('mentoringVisits');
    }

    /** @test */
    public function mentor_can_create_mentoring_visit()
    {
        $response = $this->actingAs($this->mentor)
            ->get(route('mentoring.create'));

        $response->assertStatus(200);
        $response->assertViewIs('mentoring.create');
        $response->assertViewHas(['schools', 'teachers', 'mentors']);
    }

    /** @test */
    public function teacher_cannot_create_mentoring_visit()
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('mentoring.create'));

        $response->assertStatus(403);
    }

    /** @test */
    public function mentor_can_store_valid_mentoring_visit()
    {
        $visitData = [
            'mentor_id' => $this->mentor->id,
            'school_id' => $this->school->id,
            'teacher_id' => $this->teacher->id,
            'visit_date' => Carbon::today()->format('Y-m-d'),
            'observation' => 'Great teaching observed',
            'action_plan' => 'Continue current practices',
            'score' => 85,
            'follow_up_required' => true,
            'region' => 'Central',
            'province' => 'Phnom Penh',
            'program_type' => 'TaRL Program',
            'class_in_session' => 'Yes',
            'full_session_observed' => 'Yes',
            'grade_group' => 'Std. 1-2',
            'grades_observed' => ['1', '2'],
            'subject_observed' => 'Language',
            'language_levels_observed' => ['Letter Level', 'Word'],
        ];

        $response = $this->actingAs($this->mentor)
            ->post(route('mentoring.store'), $visitData);

        $response->assertRedirect(route('mentoring.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('mentoring_visits', [
            'mentor_id' => $this->mentor->id,
            'school_id' => $this->school->id,
            'teacher_id' => $this->teacher->id,
            'observation' => 'Great teaching observed',
            'score' => 85
        ]);
    }

    /** @test */
    public function mentor_can_view_their_mentoring_visit()
    {
        $visit = MentoringVisit::factory()->create([
            'mentor_id' => $this->mentor->id,
            'teacher_id' => $this->teacher->id,
            'school_id' => $this->school->id
        ]);

        $response = $this->actingAs($this->mentor)
            ->get(route('mentoring.show', $visit));

        $response->assertStatus(200);
        $response->assertViewIs('mentoring.show');
        $response->assertViewHas('mentoringVisit');
    }

    /** @test */
    public function can_export_mentoring_visits_to_excel()
    {
        MentoringVisit::factory()->count(5)->create([
            'mentor_id' => $this->mentor->id,
            'teacher_id' => $this->teacher->id,
            'school_id' => $this->school->id
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('mentoring.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    /** @test */
    public function can_test_mentoring_visit_page_with_curl()
    {
        // Create a test visit for curl testing
        $visit = MentoringVisit::factory()->create([
            'mentor_id' => $this->mentor->id,
            'teacher_id' => $this->teacher->id,
            'school_id' => $this->school->id
        ]);

        // This simulates the curl test requirement
        $response = $this->get(route('mentoring.index'));
        
        $response->assertStatus(200);
        $this->assertStringNotContainsString('404', $response->getContent());
        $this->assertStringNotContainsString('not found', strtolower($response->getContent()));
        $this->assertStringNotContainsString('error', strtolower($response->getContent()));
    }
}
