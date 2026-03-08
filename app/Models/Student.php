<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Student extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'middle_name',
        'lrn', 'gender', 'birthdate',
        'profile_photo', 'section_id', 'teacher_id',
        'is_archived',
    ];

    protected $casts = [
        'birthdate'   => 'date',
        'is_archived' => 'boolean',
    ];

    /* ── Scopes ──────────────────────────────────── */
    public function scopeActive(Builder $q): Builder   { return $q->where('is_archived', false); }
    public function scopeArchived(Builder $q): Builder { return $q->where('is_archived', true); }

    /* ── Plain methods (not accessors) ───────────── */
    public function fullName(): string
    {
        $mid = $this->middle_name ? " {$this->middle_name}" : '';
        return "{$this->first_name}{$mid} {$this->last_name}";
    }

    public function profilePhotoUrl(): string
    {
        if ($this->profile_photo && file_exists(storage_path('app/public/' . $this->profile_photo))) {
            return asset('storage/' . $this->profile_photo);
        }
        $initials = urlencode(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
        return "https://ui-avatars.com/api/?name={$initials}&background=003A8C&color=fff&size=128";
    }

    public function philIriLabel(): string
    {
        return match($this->latestAssessment?->risk_level) {
            'Below Expected Literacy Standard'       => 'Frustration Level',
            'Approaching Expected Literacy Standard' => 'Instructional Level',
            'Meeting or Exceeding Literacy Standard' => 'Independent Level',
            default                                  => 'Not Yet Assessed',
        };
    }

    public function philIriColor(): string
    {
        return match($this->latestAssessment?->risk_level) {
            'Below Expected Literacy Standard'       => '#C8102E',
            'Approaching Expected Literacy Standard' => '#c47d0e',
            'Meeting or Exceeding Literacy Standard' => '#0d9448',
            default                                  => '#94a3b8',
        };
    }

    /* ── Relationships ───────────────────────────── */
    public function section()
    {
        return $this->belongsTo(SchoolClass::class, 'section_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

    public function latestAssessment()
    {
        return $this->hasOne(Assessment::class)->latestOfMany('assessed_on');
    }

    public function interventions()
    {
        return $this->hasMany(Intervention::class);
    }
}
