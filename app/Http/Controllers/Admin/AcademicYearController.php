<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::withCount('schoolClasses')->orderBy('start_year', 'desc')->get();
        return view('admin.academic-years.index', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'start_year' => 'required|integer',
            'end_year' => 'required|integer|gte:start_year',
        ]);

        $ay = AcademicYear::create([
            'name' => $validated['name'],
            'start_year' => $validated['start_year'],
            'end_year' => $validated['end_year'],
            'is_active' => false,
        ]);

        ActivityLog::record('ADMIN_AY_CREATED', "Admin menambahkan tahun ajaran baru '{$ay->name}'.", auth('web')->user());

        return back()->with('success', 'Tahun ajaran berhasil dibuat.');
    }

    public function toggleActive(AcademicYear $academicYear)
    {
        AcademicYear::where('is_active', true)->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);

        ActivityLog::record('ADMIN_AY_ACTIVATED', "Admin mengaktifkan tahun ajaran '{$academicYear->name}'.", auth('web')->user());

        return back()->with('success', "Tahun ajaran '{$academicYear->name}' sekarang aktif.");
    }

    public function update(Request $request, AcademicYear $academicYear)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'start_year' => 'required|integer',
            'end_year' => 'required|integer|gte:start_year',
        ]);

        $academicYear->update($validated);

        ActivityLog::record('ADMIN_AY_UPDATED', "Admin memperbarui tahun ajaran '{$academicYear->name}'.", auth('web')->user());

        return back()->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear)
    {
        if ($academicYear->schoolClasses()->exists()) {
            return back()->with('error', 'Tahun ajaran tidak bisa dihapus karena masih memiliki kelas terkait.');
        }

        if ($academicYear->is_active) {
            return back()->with('error', 'Tahun ajaran aktif tidak bisa dihapus.');
        }

        $name = $academicYear->name;
        $academicYear->delete();

        ActivityLog::record('ADMIN_AY_DELETED', "Admin menghapus tahun ajaran '{$name}'.", auth('web')->user());

        return back()->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
