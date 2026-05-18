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

    /**
     * Get performance trend data for charting (last 6 exams/years).
     */
    public function getTrendData(): array
    {
        // Group by academic_year and calculate average
        $data = \App\Models\Mark::selectRaw(
            'academic_year, ROUND(SUM(marks_obtained) / SUM(max_marks) * 100, 2) as avg_pct'
        )
            ->groupBy('academic_year')
            ->orderBy('academic_year')
            ->limit(6)
            ->get();

        return [
            'labels' => $data->pluck('academic_year')->toArray(),
            'values' => $data->pluck('avg_pct')->toArray(),
        ];
    }
}
