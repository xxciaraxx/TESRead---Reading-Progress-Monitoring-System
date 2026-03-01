<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /* ──────────────────────────────────────────────
     |  REGISTER
    ─────────────────────────────────────────────── */

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $adminAccessCode   = config('auth.codes.admin',   'TES2026Admin');
        $teacherAccessCode = config('auth.codes.teacher', 'TES2026Teacher');

        $validated = $request->validate([
            'role'     => ['required', 'in:teacher,admin'],
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->max(50)->mixedCase()->numbers()->symbols(),
            ],
            'teacher_code' => ['nullable', 'string'],
            'admin_code'   => ['nullable', 'string'],
        ], [
            'role.required'        => 'Please select a role.',
            'password.confirmed'   => 'Password confirmation does not match.',
            'email.unique'         => 'This email address is already registered.',
        ]);

        // ── Access code verification ──
        if ($validated['role'] === 'teacher') {
            if (empty($validated['teacher_code']) || trim($validated['teacher_code']) !== $teacherAccessCode) {
                return back()->withErrors(['teacher_code' => 'Invalid teacher access code. Please contact your administrator.'])->withInput();
            }
        }

        if ($validated['role'] === 'admin') {
            if (empty($validated['admin_code']) || trim($validated['admin_code']) !== $adminAccessCode) {
                return back()->withErrors(['admin_code' => 'Invalid administrator access code.'])->withInput();
            }
        }

        // ── Determine account_status ──
        // Admin registers as Approved immediately.
        // Teachers start as Pending until the admin approves them.
        $accountStatus = $validated['role'] === 'admin' ? 'Approved' : 'Pending';

        User::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'role'           => $validated['role'],
            'account_status' => $accountStatus,
        ]);

        $message = $validated['role'] === 'admin'
            ? 'Admin account created successfully! Please sign in.'
            : 'Registration successful! Your account is pending approval by the administrator. You will be notified once approved.';

        return redirect()->route('login')->with('success', $message);
    }

    /* ──────────────────────────────────────────────
     |  LOGIN
    ─────────────────────────────────────────────── */

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Find the user first so we can give specific error messages
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // ── Account status gate for teachers ──
        if ($user->isTeacher()) {
            if ($user->isPending()) {
                return back()->withErrors([
                    'email' => 'Your account is still pending approval by the administrator.',
                ])->onlyInput('email');
            }

            if ($user->isRejected()) {
                return back()->withErrors([
                    'email' => 'Your account has been rejected. Please contact the administrator.',
                ])->onlyInput('email');
            }
        }

        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        return $user->isAdmin()
            ? redirect()->intended(route('admin.dashboard'))
            : redirect()->intended(route('teacher.dashboard'));
    }

    /* ──────────────────────────────────────────────
     |  LOGOUT
    ─────────────────────────────────────────────── */

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}