<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    /**
     * The underlying database table stays "sections" — no migration rename needed.
     * The model is renamed to SchoolClass to match the UI rename "Sections → Classes".
     */
    protected $table = 'sections';

    protected $fillable = ['name', 'grade_level', 'school_year', 'teacher_id', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'section_id');
    }
}
