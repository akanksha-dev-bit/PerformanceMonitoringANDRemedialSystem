<?php
/**
 * ============================================================================
 * SlowLearnerService — Student Performance Analysis Engine
 * ============================================================================
 *
 * PURPOSE:
 *   Detects slow learners and at-risk students based on performance data.
 *   Centralizes all slow learner logic for easy access by controllers.
 *
 * HOW IT WORKS:
 *   - Filters students who have marks and are performing below standard
 *   - Supports detection by class and general system-wide detection
 *   - Provides at-risk student identification (borderline cases)
 *   - Generates summary statistics for dashboards
 *
 * KEY METHODS:
 *   - detect(): Finds all slow learners across the system
 *   - detectByClass(string $class): Filters by specific class
 *   - detectAtRisk(): Finds students between 40-50% (borderline)
 *   - getSummary(): Returns KPI counts for dashboards
 *
 * RELATED FILES:
 *   - Models:     App\Models\Student
 *   - Controllers: StudentController, TeacherController
 *   - Dashboard:  resources/views/dashboard/teacher.blade.php
 * ============================================================================
 */

namespace App\Services;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

class SlowLearnerService
{
    /**
     * Detect all slow learners across the system.
     */
    public function detect(): Collection
    {
        return Student::with(['marks.subject', 'remedialActions'])
            ->get()
            ->filter(fn(Student $s) => $s->has_marks && $s->is_slow_learner)
            ->values();
    }

    /**
     * Detect slow learners within a specific class.
     */
    public function detectByClass(string $class): Collection
    {
        return Student::where('class', $class)
            ->with(['marks.subject', 'remedialActions'])
            ->get()
            ->filter(fn(Student $s) => $s->has_marks && $s->is_slow_learner)
            ->values();
    }

    /**
     * Detect students at risk (between 40-50% average — borderline).
     */
    public function detectAtRisk(): Collection
    {
        return Student::with('marks')
            ->get()
            ->filter(function (Student $s) {
                if (!$s->has_marks) return false;
                $avg = $s->average_percentage;
                return $avg >= 40 && $avg < 60;
            })
            ->values();
    }

    /**
     * Get a summary count for dashboard KPIs.
     */
    public function getSummary(): array
    {
        $all        = Student::with('marks')->get();
        $evaluated  = $all->filter(fn($s) => $s->has_marks);
        
        $slowCount  = $evaluated->filter(fn($s) => $s->is_slow_learner)->count();
        $atRisk     = $evaluated->filter(fn($s) => $s->average_percentage >= 40 && $s->average_percentage < 60)->count();

        return [
            'total_students'  => $all->count(),
            'not_evaluated'   => $all->count() - $evaluated->count(),
            'slow_learners'   => $slowCount,
            'at_risk'         => $atRisk,
            'performing_well' => $evaluated->count() - $slowCount - $atRisk,
        ];
    }
}
