<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\AttendanceRecord;
use App\Models\LearningOutcome;
use App\Models\ProgressTracking;
use App\Models\School;
use App\Models\Student;
use App\Models\StudentIntervention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function dashboard(Request $request)
    {
        $schoolId = $request->get('school_id', auth()->user()->school_id);
        $dateRange = $request->get('date_range', 'month');

        $startDate = $this->getStartDate($dateRange);
        $endDate = now();

        $data = [
            'summary_stats' => $this->getSummaryStatistics($schoolId),
            'enrollment_trends' => $this->getEnrollmentTrends($schoolId, $startDate, $endDate),
            'attendance_overview' => $this->getAttendanceOverview($schoolId, $startDate, $endDate),
            'assessment_performance' => $this->getAssessmentPerformance($schoolId),
            'intervention_effectiveness' => $this->getInterventionEffectiveness($schoolId),
            'tarl_level_distribution' => $this->getTarlLevelDistribution($schoolId),
            'geographic_distribution' => $this->getGeographicDistribution($schoolId),
            'teacher_performance' => $this->getTeacherPerformance($schoolId),
            'learning_outcomes_progress' => $this->getLearningOutcomesProgress($schoolId),
            'at_risk_students' => $this->getAtRiskStudents($schoolId),
        ];

        return view('reports.dashboard', compact('data'));
    }

    public function studentProgress(Request $request)
    {
        $studentId = $request->get('student_id');
        $student = Student::with(['school', 'teacher', 'schoolClass'])->findOrFail($studentId);

        $data = [
            'student' => $student,
            'assessment_history' => $this->getStudentAssessmentHistory($studentId),
            'attendance_trend' => $this->getStudentAttendanceTrend($studentId),
            'learning_outcomes' => $this->getStudentLearningOutcomes($studentId),
            'intervention_history' => $this->getStudentInterventionHistory($studentId),
            'weekly_progress' => $this->getStudentWeeklyProgress($studentId),
            'strengths_weaknesses' => $this->getStudentStrengthsWeaknesses($studentId),
            'recommendations' => $this->generateStudentRecommendations($studentId),
        ];

        return view('reports.student-progress', compact('data'));
    }

    public function schoolComparison(Request $request)
    {
        $schools = School::with(['students', 'teachers'])->get();

        $data = [
            'schools' => $schools->map(function ($school) {
                return [
                    'school' => $school,
                    'metrics' => $this->getSchoolMetrics($school->id),
                    'rankings' => $this->getSchoolRankings($school->id),
                ];
            }),
            'district_averages' => $this->getDistrictAverages(),
            'province_averages' => $this->getProvinceAverages(),
        ];

        return view('reports.school-comparison', compact('data'));
    }

    public function assessmentAnalysis(Request $request)
    {
        $cycle = $request->get('cycle', 'baseline');
        $subject = $request->get('subject', 'all');
        $schoolId = $request->get('school_id');

        $data = [
            'overall_performance' => $this->getOverallAssessmentPerformance($cycle, $subject, $schoolId),
            'level_progression' => $this->getLevelProgression($cycle, $subject, $schoolId),
            'skill_analysis' => $this->getSkillAnalysis($cycle, $subject, $schoolId),
            'comparative_analysis' => $this->getComparativeAnalysis($cycle, $subject, $schoolId),
            'improvement_rates' => $this->getImprovementRates($cycle, $subject, $schoolId),
            'gender_analysis' => $this->getGenderAnalysis($cycle, $subject, $schoolId),
            'age_group_analysis' => $this->getAgeGroupAnalysis($cycle, $subject, $schoolId),
        ];

        return view('reports.assessment-analysis', compact('data'));
    }

    public function attendanceReport(Request $request)
    {
        $schoolId = $request->get('school_id');
        $startDate = $request->get('start_date', now()->subMonth());
        $endDate = $request->get('end_date', now());

        $data = [
            'daily_attendance' => $this->getDailyAttendance($schoolId, $startDate, $endDate),
            'chronic_absenteeism' => $this->getChronicAbsenteeism($schoolId),
            'attendance_by_grade' => $this->getAttendanceByGrade($schoolId, $startDate, $endDate),
            'attendance_patterns' => $this->getAttendancePatterns($schoolId, $startDate, $endDate),
            'absence_reasons' => $this->getAbsenceReasons($schoolId, $startDate, $endDate),
            'attendance_interventions' => $this->getAttendanceInterventions($schoolId),
        ];

        return view('reports.attendance', compact('data'));
    }

    public function interventionReport(Request $request)
    {
        $programId = $request->get('program_id');
        $schoolId = $request->get('school_id');

        $data = [
            'program_overview' => $this->getProgramOverview($programId),
            'enrollment_statistics' => $this->getInterventionEnrollment($programId, $schoolId),
            'success_metrics' => $this->getInterventionSuccessMetrics($programId, $schoolId),
            'student_progress' => $this->getInterventionStudentProgress($programId, $schoolId),
            'cost_effectiveness' => $this->getCostEffectiveness($programId, $schoolId),
            'recommendations' => $this->getInterventionRecommendations($programId, $schoolId),
        ];

        return view('reports.intervention', compact('data'));
    }

    public function exportReport(Request $request)
    {
        $type = $request->get('type');
        $format = $request->get('format', 'pdf');
        $filters = $request->except(['type', 'format']);

        $data = $this->generateReportData($type, $filters);

        switch ($format) {
            case 'pdf':
                return $this->exportToPdf($data, $type);
            case 'excel':
                return $this->exportToExcel($data, $type);
            case 'csv':
                return $this->exportToCsv($data, $type);
            default:
                return response()->json($data);
        }
    }

    // Helper Methods

    private function getSummaryStatistics($schoolId = null)
    {
        $query = Student::query();
        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return [
            'total_students' => $query->count(),
            'active_students' => $query->where('enrollment_status', 'active')->count(),
            'total_teachers' => \App\Models\User::where('role', 'teacher')
                ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
                ->count(),
            'total_schools' => $schoolId ? 1 : School::count(),
            'assessments_completed' => Assessment::when($schoolId, function ($q) use ($schoolId) {
                return $q->whereHas('student', fn ($sq) => $sq->where('school_id', $schoolId));
            })->count(),
            'average_attendance' => AttendanceRecord::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
                ->where('status', 'present')
                ->count() / max(AttendanceRecord::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->count(), 1) * 100,
            'students_needing_intervention' => $query->get()->filter(fn ($s) => $s->needsIntervention())->count(),
        ];
    }

    private function getEnrollmentTrends($schoolId, $startDate, $endDate)
    {
        return Student::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->whereBetween('enrollment_date', [$startDate, $endDate])
            ->selectRaw('DATE(enrollment_date) as date, COUNT(*) as count, enrollment_status')
            ->groupBy('date', 'enrollment_status')
            ->orderBy('date')
            ->get()
            ->groupBy('date')
            ->map(function ($items) {
                return [
                    'active' => $items->where('enrollment_status', 'active')->sum('count'),
                    'dropped_out' => $items->where('enrollment_status', 'dropped_out')->sum('count'),
                    'transferred' => $items->where('enrollment_status', 'transferred')->sum('count'),
                ];
            });
    }

    private function getAttendanceOverview($schoolId, $startDate, $endDate)
    {
        $records = AttendanceRecord::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->selectRaw('attendance_date, status, COUNT(*) as count')
            ->groupBy('attendance_date', 'status')
            ->get();

        return $records->groupBy('attendance_date')->map(function ($items) {
            $total = $items->sum('count');

            return [
                'date' => $items->first()->attendance_date,
                'present' => $items->where('status', 'present')->sum('count'),
                'absent' => $items->where('status', 'absent')->sum('count'),
                'late' => $items->where('status', 'late')->sum('count'),
                'excused' => $items->where('status', 'excused')->sum('count'),
                'attendance_rate' => $total > 0 ?
                    round(($items->whereIn('status', ['present', 'late'])->sum('count') / $total) * 100, 2) : 0,
            ];
        });
    }

    private function getAssessmentPerformance($schoolId)
    {
        $assessments = Assessment::with('student')
            ->when($schoolId, function ($q) use ($schoolId) {
                return $q->whereHas('student', fn ($sq) => $sq->where('school_id', $schoolId));
            })
            ->get();

        return [
            'by_cycle' => $assessments->groupBy('cycle')->map(function ($items) {
                return [
                    'average_score' => $items->avg('score'),
                    'median_score' => $items->median('score'),
                    'total_assessed' => $items->count(),
                    'by_subject' => $items->groupBy('subject')->map(function ($subItems) {
                        return [
                            'average' => $subItems->avg('score'),
                            'levels' => $subItems->groupBy('level')->map->count(),
                        ];
                    }),
                ];
            }),
            'improvement_rates' => $this->calculateImprovementRates($assessments),
        ];
    }

    private function getTarlLevelDistribution($schoolId)
    {
        $latestAssessments = Assessment::with('student')
            ->when($schoolId, function ($q) use ($schoolId) {
                return $q->whereHas('student', fn ($sq) => $sq->where('school_id', $schoolId));
            })
            ->whereIn('id', function ($q) {
                $q->select(DB::raw('MAX(id)'))
                    ->from('assessments')
                    ->groupBy('student_id', 'subject');
            })
            ->get();

        return $latestAssessments->groupBy('subject')->map(function ($items) {
            return $items->groupBy('level')->map->count();
        });
    }

    private function getInterventionEffectiveness($schoolId)
    {
        $interventions = StudentIntervention::with(['student', 'interventionProgram'])
            ->when($schoolId, function ($q) use ($schoolId) {
                return $q->whereHas('student', fn ($sq) => $sq->where('school_id', $schoolId));
            })
            ->get();

        return [
            'by_program' => $interventions->groupBy('intervention_program_id')->map(function ($items) {
                $completed = $items->whereIn('status', ['completed', 'graduated']);

                return [
                    'total_enrolled' => $items->count(),
                    'completed' => $completed->count(),
                    'success_rate' => $items->count() > 0 ?
                        round(($completed->where('goal_achieved', true)->count() / $items->count()) * 100, 2) : 0,
                    'average_duration' => $completed->avg('duration_in_weeks'),
                    'average_progress' => $items->avg('progress_score'),
                ];
            }),
            'by_type' => $interventions->groupBy('interventionProgram.type')->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'success_rate' => $items->where('goal_achieved', true)->count() / max($items->count(), 1) * 100,
                ];
            }),
        ];
    }

    private function getStudentAssessmentHistory($studentId)
    {
        return Assessment::where('student_id', $studentId)
            ->orderBy('assessed_at')
            ->get()
            ->groupBy('subject')
            ->map(function ($items) {
                return $items->map(function ($assessment) {
                    return [
                        'cycle' => $assessment->cycle,
                        'level' => $assessment->level,
                        'score' => $assessment->score,
                        'percentage' => $assessment->percentage_score,
                        'date' => $assessment->assessed_at,
                        'performance_level' => $assessment->performance_level,
                    ];
                });
            });
    }

    private function getStudentWeeklyProgress($studentId)
    {
        return ProgressTracking::where('student_id', $studentId)
            ->orderBy('academic_year', 'desc')
            ->orderBy('term', 'desc')
            ->orderBy('week_number', 'desc')
            ->limit(12)
            ->get()
            ->map(function ($progress) {
                return [
                    'week' => $progress->week_number,
                    'subject' => $progress->subject,
                    'level' => $progress->current_level,
                    'completion_rate' => $progress->completion_rate,
                    'accuracy_rate' => $progress->accuracy_rate,
                    'engagement' => $progress->engagement_score,
                    'improvement' => $progress->weekly_improvement,
                ];
            });
    }

    private function getStartDate($dateRange)
    {
        return match ($dateRange) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };
    }

    private function calculateImprovementRates($assessments)
    {
        $studentAssessments = $assessments->groupBy('student_id');

        return $studentAssessments->map(function ($items) {
            $baseline = $items->where('cycle', 'baseline')->first();
            $endline = $items->where('cycle', 'endline')->first();

            if (! $baseline || ! $endline) {
                return null;
            }

            return [
                'improvement' => $endline->score - $baseline->score,
                'percentage_change' => $baseline->score > 0 ?
                    round((($endline->score - $baseline->score) / $baseline->score) * 100, 2) : 0,
            ];
        })->filter()->values();
    }

    private function generateReportData($type, $filters)
    {
        return match ($type) {
            'summary' => $this->getSummaryStatistics($filters['school_id'] ?? null),
            'assessment' => $this->getAssessmentPerformance($filters['school_id'] ?? null),
            'attendance' => $this->getAttendanceOverview(
                $filters['school_id'] ?? null,
                $filters['start_date'] ?? now()->subMonth(),
                $filters['end_date'] ?? now()
            ),
            'intervention' => $this->getInterventionEffectiveness($filters['school_id'] ?? null),
            default => [],
        };
    }

    private function exportToPdf($data, $type)
    {
        // Implementation for PDF export
        // This would use a package like DomPDF or similar
        return response()->json(['message' => 'PDF export not yet implemented']);
    }

    private function exportToExcel($data, $type)
    {
        // Implementation for Excel export
        // This would use a package like Laravel Excel
        return response()->json(['message' => 'Excel export not yet implemented']);
    }

    private function exportToCsv($data, $type)
    {
        // Implementation for CSV export
        return response()->json(['message' => 'CSV export not yet implemented']);
    }

    private function getGeographicDistribution($schoolId)
    {
        $students = Student::with('school')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->get();

        return [
            'by_province' => $students->groupBy('home_province')->map->count(),
            'by_district' => $students->groupBy('home_district')->map->count(),
            'by_commune' => $students->groupBy('home_commune')->map->count(),
        ];
    }

    private function getTeacherPerformance($schoolId)
    {
        $teachers = \App\Models\User::where('role', 'teacher')
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->with(['students.assessments', 'students.attendanceRecords'])
            ->get();

        return $teachers->map(function ($teacher) {
            $students = $teacher->students;

            return [
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->name,
                'total_students' => $students->count(),
                'average_student_performance' => $students->flatMap->assessments->avg('score'),
                'average_attendance_rate' => $students->map(fn ($s) => $s->getCurrentAttendanceRate())->avg(),
                'students_improved' => $students->filter(function ($s) {
                    $assessments = $s->assessments->sortBy('assessed_at');
                    if ($assessments->count() < 2) {
                        return false;
                    }

                    return $assessments->last()->score > $assessments->first()->score;
                })->count(),
            ];
        });
    }

    private function getLearningOutcomesProgress($schoolId)
    {
        $outcomes = LearningOutcome::with(['studentOutcomes.student'])
            ->where('is_active', true)
            ->get();

        return $outcomes->map(function ($outcome) use ($schoolId) {
            $studentOutcomes = $outcome->studentOutcomes
                ->when($schoolId, function ($collection) use ($schoolId) {
                    return $collection->filter(fn ($so) => $so->student->school_id == $schoolId);
                });

            return [
                'outcome_code' => $outcome->code,
                'outcome_name' => $outcome->name,
                'subject' => $outcome->subject,
                'grade_level' => $outcome->grade_level,
                'mastery_rate' => $studentOutcomes->where('is_mastered', true)->count() /
                    max($studentOutcomes->count(), 1) * 100,
                'average_achievement' => $studentOutcomes->avg('achievement_score'),
                'students_attempted' => $studentOutcomes->count(),
            ];
        });
    }

    private function getAtRiskStudents($schoolId)
    {
        $students = Student::with(['assessments', 'attendanceRecords', 'interventions'])
            ->when($schoolId, fn ($q) => $q->where('school_id', $schoolId))
            ->where('enrollment_status', 'active')
            ->get();

        return $students->filter(function ($student) {
            $attendanceRate = $student->getCurrentAttendanceRate();
            $academicPerformance = $student->getAcademicPerformance();
            $hasActiveIntervention = $student->interventions->where('is_active', true)->count() > 0;

            return ($attendanceRate < 75 || $academicPerformance < 50) && ! $hasActiveIntervention;
        })->map(function ($student) {
            return [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'grade' => $student->grade,
                'attendance_rate' => $student->getCurrentAttendanceRate(),
                'academic_performance' => $student->getAcademicPerformance(),
                'risk_factors' => $this->identifyRiskFactors($student),
                'recommended_interventions' => $this->recommendInterventions($student),
            ];
        });
    }

    private function identifyRiskFactors($student)
    {
        $factors = [];

        if ($student->getCurrentAttendanceRate() < 75) {
            $factors[] = 'chronic_absenteeism';
        }

        if ($student->getAcademicPerformance() < 50) {
            $factors[] = 'low_academic_performance';
        }

        if ($student->has_disability) {
            $factors[] = 'disability';
        }

        if ($student->family_income_level === 'very_low') {
            $factors[] = 'economic_disadvantage';
        }

        if ($student->nutrition_status === 'malnourished') {
            $factors[] = 'malnutrition';
        }

        return $factors;
    }

    private function recommendInterventions($student)
    {
        $recommendations = [];
        $riskFactors = $this->identifyRiskFactors($student);

        if (in_array('chronic_absenteeism', $riskFactors)) {
            $recommendations[] = 'attendance_monitoring';
        }

        if (in_array('low_academic_performance', $riskFactors)) {
            $recommendations[] = 'academic_tutoring';
        }

        if (in_array('malnutrition', $riskFactors)) {
            $recommendations[] = 'nutrition_support';
        }

        return $recommendations;
    }

    private function getStudentLearningOutcomes($studentId)
    {
        return StudentLearningOutcome::with('learningOutcome')
            ->where('student_id', $studentId)
            ->get()
            ->map(function ($slo) {
                return [
                    'outcome' => $slo->learningOutcome->name,
                    'subject' => $slo->learningOutcome->subject,
                    'mastery_level' => $slo->mastery_level,
                    'achievement_score' => $slo->achievement_score,
                    'attempts' => $slo->attempts_count,
                    'achieved_date' => $slo->achieved_date,
                ];
            });
    }

    private function getStudentAttendanceTrend($studentId)
    {
        return AttendanceRecord::where('student_id', $studentId)
            ->orderBy('attendance_date', 'desc')
            ->limit(30)
            ->get()
            ->map(function ($record) {
                return [
                    'date' => $record->attendance_date,
                    'status' => $record->status,
                    'minutes_late' => $record->minutes_late,
                ];
            });
    }

    private function getStudentInterventionHistory($studentId)
    {
        return StudentIntervention::with('interventionProgram')
            ->where('student_id', $studentId)
            ->get()
            ->map(function ($intervention) {
                return [
                    'program' => $intervention->interventionProgram->program_name,
                    'type' => $intervention->interventionProgram->type,
                    'status' => $intervention->status,
                    'start_date' => $intervention->start_date,
                    'end_date' => $intervention->end_date,
                    'progress_score' => $intervention->progress_score,
                    'goal_achieved' => $intervention->goal_achieved,
                ];
            });
    }

    private function getStudentStrengthsWeaknesses($studentId)
    {
        $student = Student::with(['assessments', 'learningOutcomes'])->find($studentId);

        return [
            'strengths' => $student->learningOutcomes
                ->whereIn('mastery_level', ['proficient', 'advanced'])
                ->pluck('learningOutcome.name'),
            'weaknesses' => $student->learningOutcomes
                ->whereIn('mastery_level', ['not_attempted', 'emerging'])
                ->pluck('learningOutcome.name'),
        ];
    }

    private function generateStudentRecommendations($studentId)
    {
        $student = Student::with(['assessments', 'attendanceRecords', 'learningOutcomes'])->find($studentId);

        $recommendations = [];

        if ($student->getCurrentAttendanceRate() < 80) {
            $recommendations[] = [
                'type' => 'attendance',
                'priority' => 'high',
                'action' => 'Implement attendance monitoring and parent engagement strategies',
            ];
        }

        $weakSubjects = $student->assessments
            ->groupBy('subject')
            ->filter(fn ($items) => $items->avg('score') < 60)
            ->keys();

        foreach ($weakSubjects as $subject) {
            $recommendations[] = [
                'type' => 'academic',
                'priority' => 'medium',
                'action' => "Provide additional support in {$subject}",
            ];
        }

        return $recommendations;
    }

    private function getSchoolMetrics($schoolId)
    {
        $school = School::with(['students', 'teachers'])->find($schoolId);

        return [
            'total_students' => $school->students->count(),
            'active_students' => $school->students->where('enrollment_status', 'active')->count(),
            'total_teachers' => $school->teachers->count(),
            'student_teacher_ratio' => $school->teachers->count() > 0 ?
                round($school->students->count() / $school->teachers->count(), 1) : 0,
            'average_attendance' => $school->students->map(fn ($s) => $s->getCurrentAttendanceRate())->avg(),
            'average_performance' => $school->students->map(fn ($s) => $s->getAcademicPerformance())->avg(),
        ];
    }

    private function getSchoolRankings($schoolId)
    {
        // Implementation for school rankings
        return [];
    }

    private function getDistrictAverages()
    {
        // Implementation for district averages
        return [];
    }

    private function getProvinceAverages()
    {
        // Implementation for province averages
        return [];
    }

    private function getOverallAssessmentPerformance($cycle, $subject, $schoolId)
    {
        // Implementation for overall assessment performance
        return [];
    }

    private function getLevelProgression($cycle, $subject, $schoolId)
    {
        // Implementation for level progression
        return [];
    }

    private function getSkillAnalysis($cycle, $subject, $schoolId)
    {
        // Implementation for skill analysis
        return [];
    }

    private function getComparativeAnalysis($cycle, $subject, $schoolId)
    {
        // Implementation for comparative analysis
        return [];
    }

    private function getImprovementRates($cycle, $subject, $schoolId)
    {
        // Implementation for improvement rates
        return [];
    }

    private function getGenderAnalysis($cycle, $subject, $schoolId)
    {
        // Implementation for gender analysis
        return [];
    }

    private function getAgeGroupAnalysis($cycle, $subject, $schoolId)
    {
        // Implementation for age group analysis
        return [];
    }

    private function getDailyAttendance($schoolId, $startDate, $endDate)
    {
        // Implementation for daily attendance
        return [];
    }

    private function getChronicAbsenteeism($schoolId)
    {
        // Implementation for chronic absenteeism
        return [];
    }

    private function getAttendanceByGrade($schoolId, $startDate, $endDate)
    {
        // Implementation for attendance by grade
        return [];
    }

    private function getAttendancePatterns($schoolId, $startDate, $endDate)
    {
        // Implementation for attendance patterns
        return [];
    }

    private function getAbsenceReasons($schoolId, $startDate, $endDate)
    {
        // Implementation for absence reasons
        return [];
    }

    private function getAttendanceInterventions($schoolId)
    {
        // Implementation for attendance interventions
        return [];
    }

    private function getProgramOverview($programId)
    {
        // Implementation for program overview
        return [];
    }

    private function getInterventionEnrollment($programId, $schoolId)
    {
        // Implementation for intervention enrollment
        return [];
    }

    private function getInterventionSuccessMetrics($programId, $schoolId)
    {
        // Implementation for intervention success metrics
        return [];
    }

    private function getInterventionStudentProgress($programId, $schoolId)
    {
        // Implementation for intervention student progress
        return [];
    }

    private function getCostEffectiveness($programId, $schoolId)
    {
        // Implementation for cost effectiveness
        return [];
    }

    private function getInterventionRecommendations($programId, $schoolId)
    {
        // Implementation for intervention recommendations
        return [];
    }
}
