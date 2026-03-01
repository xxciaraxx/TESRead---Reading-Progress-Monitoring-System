<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Intervention;

class ReadingRiskService
{
    /* ─── Risk level constants ─── */
    const BELOW      = 'Below Expected Literacy Standard';
    const APPROACHING = 'Approaching Expected Literacy Standard';
    const MEETING    = 'Meeting or Exceeding Literacy Standard';

    /**
     * Calculate risk level based on the three rule-based criteria.
     *
     * Rule 1 — BELOW:
     *   fluency_score < 70  OR
     *   comprehension_score < 65  OR
     *   reading_sessions_per_week <= 1
     *
     * Rule 2 — APPROACHING:
     *   fluency_score < 85  OR
     *   comprehension_score < 80
     *
     * Rule 3 — MEETING (default)
     */
    public function calculateRisk(
        float $fluency,
        float $comprehension,
        int   $sessionsPerWeek
    ): string {
        if (
            $fluency       < 70  ||
            $comprehension < 65  ||
            $sessionsPerWeek <= 1
        ) {
            return self::BELOW;
        }

        if ($fluency < 85 || $comprehension < 80) {
            return self::APPROACHING;
        }

        return self::MEETING;
    }

    /**
     * Evaluate risk level and auto-create an intervention
     * if risk is BELOW.
     */
    public function evaluateAndIntervene(Assessment $assessment): void
    {
        $risk = $this->calculateRisk(
            (float) $assessment->fluency_score,
            (float) $assessment->comprehension_score,
            (int)   $assessment->reading_sessions_per_week
        );

        $assessment->update(['risk_level' => $risk]);

        // Also update the student's current reading level
        if ($assessment->student) {
            $assessment->student->update([
                'reading_level_id' => $assessment->reading_level_id,
            ]);
        }

        // Auto-create intervention only for BELOW standard
        if ($risk === self::BELOW) {
            // Avoid duplicate active interventions
            $existingActive = Intervention::where('student_id', $assessment->student_id)
                ->where('status', 'Active')
                ->exists();

            if (!$existingActive) {
                Intervention::create([
                    'student_id'          => $assessment->student_id,
                    'assessment_id'       => $assessment->id,
                    'teacher_id'          => $assessment->teacher_id,
                    'intervention_notes'  => 'Auto-created: Student is Below Expected Literacy Standard. Immediate reading intervention recommended.',
                    'status'              => 'Active',
                    'started_on'          => now()->toDateString(),
                ]);
            }
        }
    }

    /**
     * Return CSS/badge class for a given risk level string.
     */
    public function badgeClass(string $riskLevel): string
    {
        return match($riskLevel) {
            self::BELOW      => 'danger',
            self::APPROACHING => 'warning',
            self::MEETING    => 'success',
            default          => 'secondary',
        };
    }

    /**
     * Return short label for display.
     */
    public function shortLabel(string $riskLevel): string
    {
        return match($riskLevel) {
            self::BELOW      => 'Below Standard',
            self::APPROACHING => 'Approaching',
            self::MEETING    => 'Meeting Standard',
            default          => 'Not Assessed',
        };
    }
}
