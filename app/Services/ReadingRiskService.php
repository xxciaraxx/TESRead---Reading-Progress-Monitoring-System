<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Intervention;

class ReadingRiskService
{
    const BELOW       = 'Below Expected Literacy Standard';
    const APPROACHING = 'Approaching Expected Literacy Standard';
    const MEETING     = 'Meeting or Exceeding Literacy Standard';

    public function calculateRisk(float $fluency, float $comprehension, int $sessionsPerWeek): string
    {
        if ($fluency < 70 || $comprehension < 65 || $sessionsPerWeek <= 1) {
            return self::BELOW;
        }
        if ($fluency < 85 || $comprehension < 80) {
            return self::APPROACHING;
        }
        return self::MEETING;
    }

    public function evaluateAndIntervene(Assessment $assessment): void
    {
        $risk = $this->calculateRisk(
            (float) $assessment->fluency_score,
            (float) $assessment->comprehension_score,
            (int)   $assessment->reading_sessions_per_week
        );

        $assessment->update(['risk_level' => $risk]);

        if ($risk === self::BELOW) {
            $existingActive = Intervention::where('student_id', $assessment->student_id)
                ->where('status', 'Active')
                ->exists();

            if (!$existingActive) {
                Intervention::create([
                    'student_id'         => $assessment->student_id,
                    'assessment_id'      => $assessment->id,
                    'teacher_id'         => $assessment->teacher_id,
                    'intervention_notes' => 'Auto-created: Student is Below Expected Literacy Standard. Immediate reading intervention recommended.',
                    'status'             => 'Active',
                    'started_on'         => now()->toDateString(),
                ]);
            }
        }
    }

    public function badgeClass(string $riskLevel): string
    {
        return match($riskLevel) {
            self::BELOW       => 'danger',
            self::APPROACHING => 'warning',
            self::MEETING     => 'success',
            default           => 'secondary',
        };
    }

    public function shortLabel(string $riskLevel): string
    {
        return match($riskLevel) {
            self::BELOW       => 'Below Standard',
            self::APPROACHING => 'Approaching',
            self::MEETING     => 'Meeting Standard',
            default           => 'Not Assessed',
        };
    }
}
