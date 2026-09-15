<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();
        
        $query = Exam::with(['subject', 'classes'])->withCount(['questions', 'attempts']);
        if (!$user->isSuperadmin()) {
            $query->where('created_by', $user->id);
        }

        $exams = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('teacher.exams.index', compact('exams'));
    }

    public function create()
    {
        $user = Auth::guard('web')->user();
        $subjects = $user->isSuperadmin() ? Subject::all() : $user->subjects;
        $classes = SchoolClass::where('is_active', true)->with('academicYear')->get();

        return view('teacher.exams.create', compact('subjects', 'classes'));
    }

    public function store(Request $request)
    {
        $user = Auth::guard('web')->user();

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5|max:300',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'show_result' => 'nullable|boolean',
            'randomize_questions' => 'nullable|boolean',
            'randomize_options' => 'nullable|boolean',
            'class_ids' => 'required|array|min:1',
            'class_ids.*' => 'exists:classes,id',
        ], [
            'subject_id.required' => 'Mata pelajaran wajib dipilih.',
            'title.required' => 'Judul ujian wajib diisi.',
            'duration_minutes.required' => 'Durasi ujian wajib diisi.',
            'start_at.required' => 'Waktu mulai wajib diisi.',
            'end_at.required' => 'Waktu selesai wajib diisi.',
            'end_at.after' => 'Waktu selesai harus setelah waktu mulai.',
            'class_ids.required' => 'Pilih minimal satu kelas peserta.',
        ]);

        $exam = Exam::create([
            'subject_id' => $validated['subject_id'],
            'created_by' => $user->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'duration_minutes' => $validated['duration_minutes'],
            'start_at' => $validated['start_at'],
            'end_at' => $validated['end_at'],
            'status' => 'draft',
            'show_result' => $request->boolean('show_result', true),
            'randomize_questions' => $request->boolean('randomize_questions', false),
            'randomize_options' => $request->boolean('randomize_options', false),
            'is_active' => true,
        ]);

        $exam->classes()->sync($validated['class_ids']);

        ActivityLog::record('TEACHER_EXAM_CREATED', "Guru {$user->name} membuat ujian baru '{$exam->title}'.", $user);

        return redirect()->route('guru.exams.questions.index', $exam->id)->with('success', 'Ujian berhasil dibuat. Silakan tambahkan soal.');
    }

    public function edit(Exam $exam)
    {
        $this->authorizeExamAccess($exam);

        $user = Auth::guard('web')->user();
        $subjects = $user->isSuperadmin() ? Subject::all() : $user->subjects;
        $classes = SchoolClass::where('is_active', true)->with('academicYear')->get();
        $selectedClassIds = $exam->classes->pluck('id')->toArray();

        return view('teacher.exams.edit', compact('exam', 'subjects', 'classes', 'selectedClassIds'));
    }

    public function update(Request $request, Exam $exam)
    {
        $this->authorizeExamAccess($exam);

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5|max:300',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'status' => 'required|in:draft,published,ongoing,finished',
            'show_result' => 'nullable|boolean',
            'randomize_questions' => 'nullable|boolean',
            'randomize_options' => 'nullable|boolean',
            'class_ids' => 'required|array|min:1',
            'class_ids.*' => 'exists:classes,id',
        ]);

        $exam->update([
            'subject_id' => $validated['subject_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'duration_minutes' => $validated['duration_minutes'],
            'start_at' => $validated['start_at'],
            'end_at' => $validated['end_at'],
            'status' => $validated['status'],
            'show_result' => $request->boolean('show_result'),
            'randomize_questions' => $request->boolean('randomize_questions'),
            'randomize_options' => $request->boolean('randomize_options'),
        ]);

        $exam->classes()->sync($validated['class_ids']);

        ActivityLog::record('TEACHER_EXAM_UPDATED', "Ujian '{$exam->title}' diperbarui oleh " . auth('web')->user()->name, auth('web')->user());

        return redirect()->route('guru.exams.index')->with('success', 'Ujian berhasil diperbarui.');
    }

    public function destroy(Exam $exam)
    {
        $this->authorizeExamAccess($exam);

        $title = $exam->title;
        $exam->delete();

        ActivityLog::record('TEACHER_EXAM_DELETED', "Ujian '{$title}' dihapus.", auth('web')->user());

        return redirect()->route('guru.exams.index')->with('success', 'Ujian berhasil dihapus.');
    }

    public function toggleStatus(Exam $exam)
    {
        $this->authorizeExamAccess($exam);

        $newStatus = $exam->status === 'published' ? 'draft' : 'published';
        $exam->update(['status' => $newStatus]);

        return back()->with('success', "Status ujian diubah menjadi " . strtoupper($newStatus));
    }

    protected function authorizeExamAccess(Exam $exam)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isSuperadmin() && $exam->created_by !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses ke ujian ini.');
        }
    }
}
