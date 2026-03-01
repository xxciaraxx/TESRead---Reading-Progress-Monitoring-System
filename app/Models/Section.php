<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['name', 'grade_level', 'school_year', 'teacher_id', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
