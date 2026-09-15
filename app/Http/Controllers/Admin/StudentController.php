<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\StudentImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('schoolClass');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('nis', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'archived') {
                $query->where('is_active', false)->orWhereNotNull('archived_at');
            }
        }

        $students = $query->orderBy('name', 'asc')->paginate(20)->withQueryString();
        $classes = SchoolClass::where('is_active', true)->orderBy('name', 'asc')->get();

        return view('admin.students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('admin.students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nis' => 'required|string|unique:students,nis',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'class_id' => 'required|exists:classes,id',
            'password' => 'required|string|min:4',
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS sudah digunakan oleh siswa lain.',
            'name.required' => 'Nama siswa wajib diisi.',
            'class_id.required' => 'Kelas wajib dipilih.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $student = Student::create([
            'nis' => $validated['nis'],
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'class_id' => $validated['class_id'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        ActivityLog::record('ADMIN_STUDENT_CREATED', "Admin membuat data siswa baru {$student->name} (NIS: {$student->nis}).", auth('web')->user());

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'class_id' => 'required|exists:classes,id',
            'password' => 'nullable|string|min:4',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'nis' => $validated['nis'],
            'name' => $validated['name'],
            'gender' => $validated['gender'],
            'class_id' => $validated['class_id'],
            'is_active' => $request->boolean('is_active', true),
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $student->update($data);

        ActivityLog::record('ADMIN_STUDENT_UPDATED', "Admin memperbarui data siswa {$student->name}.", auth('web')->user());

        return redirect()->route('admin.students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $name = $student->name;
        $student->delete();

        ActivityLog::record('ADMIN_STUDENT_DELETED', "Admin menghapus data siswa {$name}.", auth('web')->user());

        return back()->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * CSV Import view and action.
     */
    public function showImportForm()
    {
        $classes = SchoolClass::where('is_active', true)->orderBy('name', 'asc')->get();
        return view('admin.students.import', compact('classes'));
    }

    public function processImport(Request $request, StudentImportService $importService)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
            'default_class_id' => 'nullable|exists:classes,id',
        ], [
            'file.required' => 'Pilih file CSV untuk di-import.',
            'file.mimes' => 'Format file harus .csv atau .txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        
        $header = fgetcsv($handle, 1000, ',');
        $rows = [];

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            if (count($data) >= 4) {
                $rows[] = [
                    'nis' => $data[0] ?? '',
                    'name' => $data[1] ?? '',
                    'gender' => $data[2] ?? 'L',
                    'password' => $data[3] ?? '123456',
                    'class_name' => $data[4] ?? null,
                ];
            }
        }
        fclose($handle);

        $result = $importService->import($rows, $request->default_class_id);

        ActivityLog::record(
            'ADMIN_STUDENT_IMPORTED',
            "Admin melakukan import siswa. Berhasil: {$result['success']}, Gagal: {$result['failed']}.",
            auth('web')->user()
        );

        return view('admin.students.import-result', compact('result'));
    }

    /**
     * Reset student password.
     */
    public function resetPassword(Request $request, Student $student)
    {
        $newPassword = $request->input('password', '123456');
        $student->update([
            'password' => Hash::make($newPassword),
        ]);

        ActivityLog::record('ADMIN_RESET_STUDENT_PASS', "Admin mereset password siswa {$student->name}.", auth('web')->user());

        return back()->with('success', "Password siswa {$student->name} berhasil direset menjadi '{$newPassword}'.");
    }
}
