<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ActivityLog;
use App\Models\Major;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::with(['academicYear', 'major'])
            ->withCount('students')
            ->orderBy('grade', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $majors = Major::orderBy('code', 'asc')->get();

        return view('admin.classes.create', compact('academicYears', 'majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'major_id' => 'required|exists:majors,id',
            'grade' => 'required|in:X,XI,XII',
            'name' => 'required|string|max:255',
            'class_number' => 'required|integer|min:1',
        ]);

        $schoolClass = SchoolClass::create([
            'academic_year_id' => $validated['academic_year_id'],
            'major_id' => $validated['major_id'],
            'grade' => $validated['grade'],
            'name' => $validated['name'],
            'class_number' => $validated['class_number'],
            'is_active' => true,
        ]);

        ActivityLog::record('ADMIN_CLASS_CREATED', "Admin membuat kelas baru '{$schoolClass->name}'.", auth('web')->user());

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil dibuat.');
    }

    public function edit(SchoolClass $class)
    {
        $academicYears = AcademicYear::orderBy('start_year', 'desc')->get();
        $majors = Major::orderBy('code', 'asc')->get();

        return view('admin.classes.edit', compact('class', 'academicYears', 'majors'));
    }

    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'major_id' => 'required|exists:majors,id',
            'grade' => 'required|in:X,XI,XII',
            'name' => 'required|string|max:255',
            'class_number' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $class->update([
            'academic_year_id' => $validated['academic_year_id'],
            'major_id' => $validated['major_id'],
            'grade' => $validated['grade'],
            'name' => $validated['name'],
            'class_number' => $validated['class_number'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLog::record('ADMIN_CLASS_UPDATED', "Admin memperbarui kelas '{$class->name}'.", auth('web')->user());

        return redirect()->route('admin.classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $class)
    {
        $name = $class->name;
        $class->delete();

        ActivityLog::record('ADMIN_CLASS_DELETED', "Admin menghapus kelas '{$name}'.", auth('web')->user());

        return back()->with('success', 'Kelas berhasil dihapus.');
    }
}
