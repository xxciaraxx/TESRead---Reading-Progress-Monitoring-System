<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
        'role', 'account_status',
        'profile_photo', 'bio', 'phone',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /* ── Role helpers ── */
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isTeacher(): bool  { return $this->role === 'teacher'; }

    /* ── Status helpers ── */
    public function isApproved(): bool { return $this->account_status === 'Approved'; }
    public function isPending(): bool  { return $this->account_status === 'Pending'; }
    public function isRejected(): bool { return $this->account_status === 'Rejected'; }
    public function canLogin(): bool   { return $this->isAdmin() || $this->isApproved(); }

    /* ── Profile photo URL ── */
    public function profilePhotoUrl(): string
    {
        if ($this->profile_photo && file_exists(storage_path('app/public/' . $this->profile_photo))) {
            return asset('storage/' . $this->profile_photo);
        }
        $initials = urlencode(substr($this->name, 0, 2));
        return "https://ui-avatars.com/api/?name={$initials}&background=003A8C&color=fff&size=128";
    }

    /* ── Relationships ── */
    public function sections()
    {
        return $this->hasMany(Section::class, 'teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'teacher_id');
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'teacher_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}
