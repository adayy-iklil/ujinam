<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    public function index()
    {
        $majors = Major::withCount('schoolClasses')->orderBy('code', 'asc')->get();
        return view('admin.majors.index', compact('majors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:majors,code',
            'name' => 'required|string|max:255',
        ]);

        $major = Major::create($validated);
        ActivityLog::record('ADMIN_MAJOR_CREATED', "Admin membuat jurusan baru '{$major->name}' ({$major->code}).", auth('web')->user());

        return back()->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, Major $major)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:10|unique:majors,code,' . $major->id,
            'name' => 'required|string|max:255',
        ]);

        $major->update($validated);
        ActivityLog::record('ADMIN_MAJOR_UPDATED', "Admin memperbarui jurusan '{$major->name}'.", auth('web')->user());

        return back()->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Major $major)
    {
        $name = $major->name;
        $major->delete();
        ActivityLog::record('ADMIN_MAJOR_DELETED', "Admin menghapus jurusan '{$name}'.", auth('web')->user());

        return back()->with('success', 'Jurusan berhasil dihapus.');
    }
}
