<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadingLevel extends Model
{
    protected $fillable = ['name', 'description', 'grade_level', 'color_code', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }
}
