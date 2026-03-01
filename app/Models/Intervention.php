<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $fillable = [
        'student_id', 'assessment_id', 'teacher_id',
        'intervention_notes', 'status',
        'started_on', 'ended_on',
    ];

    protected $casts = [
        'started_on' => 'date',
        'ended_on'   => 'date',
    ];

    public function student()     { return $this->belongsTo(Student::class); }
    public function assessment()  { return $this->belongsTo(Assessment::class); }
    public function teacher()     { return $this->belongsTo(User::class, 'teacher_id'); }
}
