<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Assessment;
use App\Models\ActivityLog;
use App\Services\ReadingRiskService;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Teacher stats
        $totalTeachers    = User::where('role', 'teacher')->count();
        $pendingTeachers  = User::where('role', 'teacher')->where('account_status', 'Pending')->count();
        $approvedTeachers = User::where('role', 'teacher')->where('account_status', 'Approved')->count();
        $rejectedTeachers = User::where('role', 'teacher')->where('account_status', 'Rejected')->count();

        // Student stats
        $totalStudents = Student::active()->count();

        $meeting    = Assessment::where('risk_level', ReadingRiskService::MEETING)->count();
        $approaching = Assessment::where('risk_level', ReadingRiskService::APPROACHING)->count();
        $below       = Assessment::where('risk_level', ReadingRiskService::BELOW)->count();

        // Recent activity
        $recentLogs = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Recent teacher registrations
        $recentTeachers = User::where('role', 'teacher')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalTeachers', 'pendingTeachers', 'approvedTeachers', 'rejectedTeachers',
            'totalStudents', 'meeting', 'approaching', 'below',
            'recentLogs', 'recentTeachers'
        ));
    }
}
