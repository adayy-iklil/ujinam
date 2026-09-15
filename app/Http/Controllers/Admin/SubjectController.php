<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::withCount(['exams', 'teachers'])->orderBy('name', 'asc')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $subject = Subject::create([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        ActivityLog::record('ADMIN_SUBJECT_CREATED', "Admin membuat mata pelajaran '{$subject->name}' ({$subject->code}).", auth('web')->user());

        return back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $subject->update([
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::record('ADMIN_SUBJECT_UPDATED', "Admin memperbarui mata pelajaran '{$subject->name}'.", auth('web')->user());

        return back()->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject)
    {
        $name = $subject->name;
        $subject->delete();

        ActivityLog::record('ADMIN_SUBJECT_DELETED', "Admin menghapus mata pelajaran '{$name}'.", auth('web')->user());

        return back()->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
