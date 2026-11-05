<?php
/**
 * ============================================================================
 * PerformanceService — Automated Student Evaluation Engine
 * ============================================================================
 *
 * PURPOSE:
 *   Centralized service for all student performance calculations:
 *   - Grading from marks
 *   - Detecting slow learners
 *   - Generating performance labels
 *   - Trend analysis
 *
 * KEY FEATURES:
 *   - Pass threshold: 40% (configurable in config/app.php)
 *   - Student labels: "good", "at_risk", "slow_learner"
 *   - Auto-generates performance reports without manual grading
 *
 * ROUTES AFFECTED:
 *   - None directly - called by controllers: 
 *     StudentController (updateStatus), TeacherController (updateStudentStatus)
 *
 * RELATED FILES:
 *   - Models:     App\Models\Student, App\Models\Mark
 *   - Config:     config/app.php (PASS_THRESHOLD constant)
 *   - Controllers: StudentController, TeacherController
 * ============================================================================
 */

namespace App\Services;

use App\Models\Student;

class PerformanceService
{
    /**
     * Get performance summary for a single student.
     */
    public function getStudentSummary(Student $student): array
    {
        $marks = $student->marks()->with('subject')->get();

        $subjectData = $marks->map(function ($mark) {
            return [
                'subject'         => $mark->subject->name ?? 'N/A',
                'marks_obtained'  => $mark->marks_obtained,
                'max_marks'       => $mark->max_marks,
                'percentage'      => $mark->percentage,
                'is_pass'         => $mark->is_pass,
                'exam_type'       => $mark->exam_type,
            ];
        });

        $totalObtained = $marks->sum('marks_obtained');
        $totalMax      = $marks->sum('max_marks');
        $avg           = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;

        return [
            'student'          => $student,
            'subject_data'     => $subjectData,
            'average'          => $avg,
            'total_obtained'   => $totalObtained,
            'total_max'        => $totalMax,
            'is_slow_learner'  => $student->is_slow_learner,
            'performance_label'=> $student->performance_label,
        ];
    }

    /**
     * Get class-wide performance statistics.
     */
    public function getClassSummary(string $class): array
    {
        $students = Student::where('class', $class)
            ->with('marks')
            ->get();

        $stats = [
            'good'          => 0,
            'at_risk'       => 0,
            'slow'          => 0,
            'not_evaluated' => 0,
        ];

        foreach ($students as $student) {
            $stats[$student->performance_status]++;
        }

        return [
            'total'         => $students->count(),
            'good'          => $stats['good'],
            'at_risk'       => $stats['at_risk'],
            'slow_learners' => $stats['slow'],
            'not_evaluated' => $stats['not_evaluated'],
        ];
    }

    /** Average marks by actual assessment type and academic year, most recent first. */
    public function getTrendData(): array
    {
        return \App\Models\Mark::query()
            ->where('max_marks', '>', 0)
            ->selectRaw('academic_year, exam_type, ROUND(SUM(marks_obtained) * 100.0 / SUM(max_marks), 2) as avg_pct, COUNT(*) as marks_count, MAX(created_at) as assessed_at')
            ->groupBy('academic_year', 'exam_type')
            ->orderByDesc('assessed_at')
            ->limit(12)
            ->get()
            ->reverse()
            ->values()
            ->map(fn ($row) => [
                'label' => $row->academic_year . ' · ' . match ($row->exam_type) {
                    'unit_test' => 'Unit test', 'midterm' => 'Mid-term',
                    'final' => 'Finals', 'practical' => 'Practical',
                    default => ucfirst(str_replace('_', ' ', $row->exam_type)),
                },
                'exam_type' => $row->exam_type,
                'avg' => (float) $row->avg_pct,
                'count' => (int) $row->marks_count,
            ])->all();
    }
}
