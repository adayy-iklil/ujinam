<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Exam;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function index(Exam $exam)
    {
        $this->authorizeExamAccess($exam);

        $questions = $exam->questions()->with('options')->orderBy('question_order', 'asc')->get();

        return view('teacher.questions.index', compact('exam', 'questions'));
    }

    public function create(Exam $exam)
    {
        $this->authorizeExamAccess($exam);
        $nextOrder = $exam->questions()->count() + 1;

        return view('teacher.questions.create', compact('exam', 'nextOrder'));
    }

    public function store(Request $request, Exam $exam)
    {
        $this->authorizeExamAccess($exam);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'points' => 'required|numeric|min:0.1',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D,E',
        ], [
            'question_text.required' => 'Teks pertanyaan wajib diisi.',
            'points.required' => 'Bobot poin wajib diisi.',
            'options.required' => 'Pilihan jawaban wajib diisi.',
            'correct_option.required' => 'Pilih jawaban yang benar.',
        ]);

        DB::transaction(function () use ($request, $exam, $validated) {
            $nextOrder = $exam->questions()->count() + 1;

            $question = Question::create([
                'exam_id' => $exam->id,
                'question_text' => $validated['question_text'],
                'question_type' => 'multiple_choice',
                'points' => $validated['points'],
                'question_order' => $nextOrder,
            ]);

            $keys = ['A', 'B', 'C', 'D', 'E'];
            foreach ($keys as $idx => $key) {
                if (isset($validated['options'][$key])) {
                    QuestionOption::create([
                        'question_id' => $question->id,
                        'option_key' => $key,
                        'option_text' => $validated['options'][$key],
                        'is_correct' => $key === $validated['correct_option'],
                        'option_order' => $idx + 1,
                    ]);
                }
            }
        });

        ActivityLog::record('TEACHER_QUESTION_CREATED', "Guru menambahkan soal baru pada ujian '{$exam->title}'.", Auth::guard('web')->user());

        return redirect()->route('guru.exams.questions.index', $exam->id)->with('success', 'Soal berhasil ditambahkan.');
    }

    public function edit(Exam $exam, Question $question)
    {
        $this->authorizeExamAccess($exam);
        $options = $question->options->keyBy('option_key');

        return view('teacher.questions.edit', compact('exam', 'question', 'options'));
    }

    public function update(Request $request, Exam $exam, Question $question)
    {
        $this->authorizeExamAccess($exam);

        $validated = $request->validate([
            'question_text' => 'required|string',
            'points' => 'required|numeric|min:0.1',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_option' => 'required|in:A,B,C,D,E',
        ]);

        DB::transaction(function () use ($question, $validated) {
            $question->update([
                'question_text' => $validated['question_text'],
                'points' => $validated['points'],
            ]);

            $keys = ['A', 'B', 'C', 'D', 'E'];
            foreach ($keys as $idx => $key) {
                if (isset($validated['options'][$key])) {
                    QuestionOption::updateOrCreate(
                        [
                            'question_id' => $question->id,
                            'option_key' => $key,
                        ],
                        [
                            'option_text' => $validated['options'][$key],
                            'is_correct' => $key === $validated['correct_option'],
                            'option_order' => $idx + 1,
                        ]
                    );
                }
            }
        });

        ActivityLog::record('TEACHER_QUESTION_UPDATED', "Guru memperbarui soal pada ujian '{$exam->title}'.", Auth::guard('web')->user());

        return redirect()->route('guru.exams.questions.index', $exam->id)->with('success', 'Soal berhasil diperbarui.');
    }

    public function destroy(Exam $exam, Question $question)
    {
        $this->authorizeExamAccess($exam);

        $question->delete();

        ActivityLog::record('TEACHER_QUESTION_DELETED', "Guru menghapus soal pada ujian '{$exam->title}'.", Auth::guard('web')->user());

        return back()->with('success', 'Soal berhasil dihapus.');
    }

    protected function authorizeExamAccess(Exam $exam)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isSuperadmin() && $exam->created_by !== $user->id) {
            abort(403);
        }
    }
}
