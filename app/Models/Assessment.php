<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'student_id', 'teacher_id', 'reading_level_id',
        'fluency_score', 'comprehension_score',
        'reading_sessions_per_week', 'notes',
        'risk_level', 'assessed_on',
    ];

    protected $casts = [
        'assessed_on'              => 'date',
        'fluency_score'            => 'decimal:2',
        'comprehension_score'      => 'decimal:2',
    ];

    /* ── Risk level badge class ── */
    public function riskBadgeClass(): string
    {
        return match($this->risk_level) {
            'Below Expected Literacy Standard'           => 'badge-danger',
            'Approaching Expected Literacy Standard'     => 'badge-warning',
            'Meeting or Exceeding Literacy Standard'     => 'badge-success',
            default                                      => 'badge-secondary',
        };
    }

    /* ── Relationships ── */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function readingLevel()
    {
        return $this->belongsTo(ReadingLevel::class);
    }

    public function intervention()
    {
        return $this->hasOne(Intervention::class);
    }
}
