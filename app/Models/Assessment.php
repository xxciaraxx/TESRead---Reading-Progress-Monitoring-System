<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = [
        'student_id', 'teacher_id',
        'fluency_score', 'comprehension_score',
        'notes', 'risk_level', 'assessed_on',
    ];

    protected $casts = [
        'assessed_on'         => 'date',
        'fluency_score'       => 'float',
        'comprehension_score' => 'float',
    ];

    public function philIriLabel(): string
    {
        return match($this->risk_level) {
            'Below Expected Literacy Standard'       => 'Frustration Level',
            'Approaching Expected Literacy Standard' => 'Instructional Level',
            'Meeting or Exceeding Literacy Standard' => 'Independent Level',
            default                                  => 'Not Yet Assessed',
        };
    }

    public function philIriColor(): string
    {
        return match($this->risk_level) {
            'Below Expected Literacy Standard'       => '#C8102E',
            'Approaching Expected Literacy Standard' => '#c47d0e',
            'Meeting or Exceeding Literacy Standard' => '#0d9448',
            default                                  => '#94a3b8',
        };
    }

    /* ── Relationships ───────────────────────────── */
    public function student()      { return $this->belongsTo(Student::class); }
    public function teacher()      { return $this->belongsTo(User::class, 'teacher_id'); }
    public function intervention() { return $this->hasOne(Intervention::class); }
}