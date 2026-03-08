<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\ProfileController;
use App\Http\Controllers\Teacher\AssessmentController;
use App\Http\Controllers\Teacher\InterventionController;
use App\Http\Controllers\Teacher\ReportController;
use App\Http\Controllers\Teacher\StudentController as TeacherStudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', fn () => view('auth.landing'))->name('landing');

/*── Guest ───────────────────────────────────────────────*/
Route::get('/register', function (Request $request) {
    if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return app(AuthController::class)->showRegisterForm();
})->name('register');

Route::get('/login', function (Request $request) {
    if (Auth::check()) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return app(AuthController::class)->showLoginForm();
})->name('login');

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/forgot-password',  [\App\Http\Controllers\Auth\PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetController::class, 'sendResetToken'])->name('password.email');
    Route::get('/reset-password',   [\App\Http\Controllers\Auth\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password',  [\App\Http\Controllers\Auth\PasswordResetController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->post('/logout', [AuthController::class, 'logout'])->name('logout');

/*── Admin ───────────────────────────────────────────────*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('teachers', TeacherController::class);
    Route::patch('teachers/{teacher}/approve',    [TeacherController::class, 'approve'])->name('teachers.approve');
    Route::patch('teachers/{teacher}/reject',     [TeacherController::class, 'reject'])->name('teachers.reject');
    Route::patch('teachers/{teacher}/deactivate', [TeacherController::class, 'deactivate'])->name('teachers.deactivate');
    Route::patch('teachers/{teacher}/reactivate', [TeacherController::class, 'reactivate'])->name('teachers.reactivate');

    Route::resource('classes', ClassController::class);
    Route::patch('classes/{class}/archive',  [ClassController::class, 'archive'])->name('classes.archive');
    Route::patch('classes/{class}/restore',  [ClassController::class, 'restore'])->name('classes.restore');

    Route::resource('students', StudentController::class);
    Route::patch('students/{student}/archive', [StudentController::class, 'archive'])->name('students.archive');
    Route::patch('students/{student}/restore', [StudentController::class, 'restore'])->name('students.restore');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Analytics & Reports
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
});

/*── Teacher ─────────────────────────────────────────────*/
Route::prefix('teacher')->name('teacher.')->middleware(['auth', 'teacher'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile',           [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',           [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo',    [ProfileController::class, 'uploadPhoto'])->name('profile.photo');
    Route::put('/profile/password',  [ProfileController::class, 'changePassword'])->name('profile.password');

    // Students — full resource
    Route::resource('students', TeacherStudentController::class);
    Route::patch('students/{student}/archive', [TeacherStudentController::class, 'archive'])->name('students.archive');
    Route::patch('students/{student}/restore', [TeacherStudentController::class, 'restore'])->name('students.restore');

    // Assessments
    Route::resource('assessments', AssessmentController::class)
         ->only(['index', 'create', 'store', 'show']);
    Route::get('assessments/student/{student}', [AssessmentController::class, 'studentHistory'])
         ->name('assessments.student-history');

    // Interventions
    Route::resource('interventions', InterventionController::class)
         ->only(['index', 'show', 'update']);
    Route::patch('interventions/{intervention}/complete',
         [InterventionController::class, 'complete'])->name('interventions.complete');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});
