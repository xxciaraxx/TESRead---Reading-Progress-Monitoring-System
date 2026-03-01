<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in.');
        }

        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403, 'Teacher access only.');
        }

        if (!$user->isApproved()) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $msg = $user->isPending()
                ? 'Your account is pending admin approval.'
                : 'Your account has been rejected. Contact the administrator.';

            return redirect()->route('login')->with('error', $msg);
        }

        return $next($request);
    }
}