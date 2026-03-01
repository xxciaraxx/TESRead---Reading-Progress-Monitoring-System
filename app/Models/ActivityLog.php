<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'subject', 'ip_address', 'details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* ── Static helper to create a log entry ── */
    public static function log(string $action, string $subject = '', string $details = ''): void
    {
        static::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'subject'    => $subject,
            'ip_address' => request()->ip(),
            'details'    => $details,
        ]);
    }
}
