<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'guru')
            ->with('subjects')
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::where('is_active', true)->get();
        return view('admin.teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
        ], [
            'username.unique' => 'Username sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $teacher = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'guru',
            'is_active' => true,
        ]);

        if (!empty($validated['subject_ids'])) {
            $teacher->subjects()->sync($validated['subject_ids']);
        }

        ActivityLog::record('ADMIN_TEACHER_CREATED', "Admin membuat akun guru baru {$teacher->name}.", auth('web')->user());

        return redirect()->route('admin.teachers.index')->with('success', 'Akun guru berhasil dibuat.');
    }

    public function edit(User $teacher)
    {
        if ($teacher->role !== 'guru') {
            abort(404);
        }

        $subjects = Subject::where('is_active', true)->get();
        $selectedSubjectIds = $teacher->subjects->pluck('id')->toArray();

        return view('admin.teachers.edit', compact('teacher', 'subjects', 'selectedSubjectIds'));
    }

    public function update(Request $request, User $teacher)
    {
        if ($teacher->role !== 'guru') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $teacher->id,
            'email' => 'nullable|email|unique:users,email,' . $teacher->id,
            'password' => 'nullable|string|min:6',
            'is_active' => 'nullable|boolean',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $teacher->update($data);

        if (isset($validated['subject_ids'])) {
            $teacher->subjects()->sync($validated['subject_ids']);
        }

        ActivityLog::record('ADMIN_TEACHER_UPDATED', "Admin memperbarui data guru {$teacher->name}.", auth('web')->user());

        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(User $teacher)
    {
        if ($teacher->role !== 'guru') {
            abort(404);
        }

        $name = $teacher->name;
        $teacher->delete();

        ActivityLog::record('ADMIN_TEACHER_DELETED', "Admin menghapus akun guru {$name}.", auth('web')->user());

        return back()->with('success', 'Akun guru berhasil dihapus.');
    }
}
