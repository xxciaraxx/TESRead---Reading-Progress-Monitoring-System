<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'teacher');

        if ($request->filled('status')) {
            $query->where('account_status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $teachers = $query->latest()->paginate(15)->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|string|min:8|confirmed',
            'account_status' => 'required|in:Pending,Approved,Rejected',
            'profile_photo'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('profile_photos', 'public');
        }

        $data['password'] = Hash::make($data['password']);
        $data['role']     = 'teacher';

        $teacher = User::create($data);

        ActivityLog::log('Created teacher account', "Teacher: {$teacher->name}");

        return redirect()->route('admin.teachers.index')
            ->with('success', "Teacher '{$teacher->name}' created successfully.");
    }

    public function show(User $teacher)
    {
        abort_if($teacher->role !== 'teacher', 404);
        $teacher->load('students', 'sections');
        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(User $teacher)
    {
        abort_if($teacher->role !== 'teacher', 404);
        return view('admin.teachers.edit', compact('teacher'));
    }

    public function update(Request $request, User $teacher)
    {
        abort_if($teacher->role !== 'teacher', 404);

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => ['required', 'email', Rule::unique('users')->ignore($teacher->id)],
            'account_status' => 'required|in:Pending,Approved,Rejected',
            'profile_photo'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'       => 'nullable|string|min:8|confirmed',
        ]);

        if ($request->hasFile('profile_photo')) {
            // Delete old
            if ($teacher->profile_photo) {
                Storage::disk('public')->delete($teacher->profile_photo);
            }
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('profile_photos', 'public');
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $oldStatus = $teacher->account_status;
        $teacher->update($data);

        if ($oldStatus !== $teacher->account_status) {
            ActivityLog::log(
                "Changed teacher status to {$teacher->account_status}",
                "Teacher: {$teacher->name}"
            );
        } else {
            ActivityLog::log('Updated teacher account', "Teacher: {$teacher->name}");
        }

        return redirect()->route('admin.teachers.index')
            ->with('success', "Teacher '{$teacher->name}' updated successfully.");
    }

    public function destroy(User $teacher)
    {
        abort_if($teacher->role !== 'teacher', 404);

        if ($teacher->profile_photo) {
            Storage::disk('public')->delete($teacher->profile_photo);
        }

        $name = $teacher->name;
        $teacher->delete();

        ActivityLog::log('Deleted teacher account', "Teacher: {$name}");

        return redirect()->route('admin.teachers.index')
            ->with('success', "Teacher '{$name}' deleted.");
    }

    /* ── Quick approval actions ── */
    public function approve(User $teacher)
    {
        abort_if($teacher->role !== 'teacher', 404);
        $teacher->update(['account_status' => 'Approved']);
        ActivityLog::log('Approved teacher account', "Teacher: {$teacher->name}");
        return back()->with('success', "{$teacher->name} has been approved.");
    }

    public function reject(User $teacher)
    {
        abort_if($teacher->role !== 'teacher', 404);
        $teacher->update(['account_status' => 'Rejected']);
        ActivityLog::log('Rejected teacher account', "Teacher: {$teacher->name}");
        return back()->with('success', "{$teacher->name} has been rejected.");
    }
}
