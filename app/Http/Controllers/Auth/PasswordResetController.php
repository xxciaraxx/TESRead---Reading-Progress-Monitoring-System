<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    // Show "Forgot Password" form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Handle email submission — generate token & show it on screen
    public function sendResetToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No account found with that email address.',
        ]);

        $email = $request->email;

        // Delete any old token for this email
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Generate a 6-digit PIN token (easy to type on any device)
        $token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->insert([
            'email'      => $email,
            'token'      => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Return to forgot-password page showing the token (no email needed)
        return back()->with([
            'reset_token' => $token,
            'reset_email' => $email,
        ]);
    }

    // Show reset password form
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->query('email', ''),
        ]);
    }

    // Handle new password submission
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users,email',
            'token'    => 'required|string|size:6',
            'password' => ['required', 'confirmed', Password::min(8)->max(50)->mixedCase()->numbers()->symbols()],
        ], [
            'token.size' => 'The reset code must be exactly 6 digits.',
        ]);

        // Find the token record
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record) {
            return back()->withErrors(['token' => 'No reset request found for this email.'])->withInput();
        }

        // Check expiry — 1 minute
        if (Carbon::parse($record->created_at)->addMinutes(1)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'This reset code has expired. Please request a new one.'])->withInput();
        }

        // Verify token
        if (! Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'Incorrect reset code. Please try again.'])->withInput();
        }

        // Update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Clean up token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Password reset successfully! You can now sign in with your new password.');
    }
}