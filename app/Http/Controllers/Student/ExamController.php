<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAttemptQuestion;
use App\Models\ExamViolation;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamController extends Controller
{
    /**
     * Show exam instruction page before starting.
     */
    public function showInstruction(Exam $exam)
    {
        $student = Auth::guard('student')->user();

        // Verify class assignment
        if (!$exam->classes->contains($student->class_id)) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda tidak memiliki akses ke ujian ini.');
        }

        $attempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        return view('student.exam-instruction', compact('exam', 'attempt', 'student'));
    }

    /**
     * Start a new exam attempt or resume existing attempt.
     */
    public function startExam(Exam $exam)
    {
        $student = Auth::guard('student')->user();

        if (!$exam->classes->contains($student->class_id)) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda tidak memiliki akses ke ujian ini.');
        }

        if (!$exam->is_active || !in_array($exam->status, ['published', 'ongoing'])) {
            return redirect()->route('siswa.dashboard')->with('error', 'Ujian belum aktif atau tidak tersedia.');
        }

        $existingAttempt = ExamAttempt::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingAttempt) {
            if ($existingAttempt->status === 'submitted') {
                return redirect()->route('siswa.dashboard')->with('error', 'Anda telah menyelesaikan ujian ini.');
            }
            if ($existingAttempt->isLocked()) {
                return redirect()->route('siswa.dashboard')->with('error', 'Sesi ujian Anda terkunci karena pelanggaran. Silakan hubungi pengawas.');
            }
            return redirect()->route('siswa.attempts.show', $existingAttempt->id);
        }

        // Check if timeframe is valid
        $now = now();
        if ($now->lessThan($exam->start_at)) {
            return redirect()->route('siswa.dashboard')->with('error', 'Ujian belum dimulai.');
        }
        if ($now->greaterThan($exam->end_at)) {
            return redirect()->route('siswa.dashboard')->with('error', 'Waktu ujian telah berakhir.');
        }

        // Create attempt inside database transaction
        $attempt = DB::transaction(function () use ($exam, $student) {
            $startedAt = now();
            $expiresAt = (clone $startedAt)->addMinutes($exam->duration_minutes);
            if ($expiresAt->greaterThan($exam->end_at)) {
                $expiresAt = $exam->end_at;
            }

            $attempt = ExamAttempt::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'started_at' => $startedAt,
                'expires_at' => $expiresAt,
                'status' => 'in_progress',
                'violation_count' => 0,
            ]);

            // Deterministic Question Ordering
            $questions = $exam->questions;
            if ($exam->randomize_questions) {
                $questions = $questions->shuffle();
            }

            foreach ($questions as $order => $question) {
                ExamAttemptQuestion::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'question_order' => $order + 1,
                ]);
            }

            ActivityLog::record('STUDENT_EXAM_STARTED', "Siswa {$student->name} memulai ujian '{$exam->title}'.", null, $student);

            return $attempt;
        });

        return redirect()->route('siswa.attempts.show', $attempt->id);
    }

    /**
     * Render the active examination page.
     */
    public function showAttempt(ExamAttempt $attempt)
    {
        $student = Auth::guard('student')->user();

        if ($attempt->student_id !== $student->id) {
            abort(403, 'Akses tidak diizinkan.');
        }

        if ($attempt->status === 'submitted') {
            return redirect()->route('siswa.dashboard')->with('error', 'Sesi ujian ini telah diselesaikan.');
        }

        if ($attempt->isLocked()) {
            return view('student.exam-locked', compact('attempt'));
        }

        if ($attempt->isExpired()) {
            // Auto submit if expired
            return $this->processSubmission($attempt, true);
        }

        $attemptQuestions = $attempt->attemptQuestions()
            ->with(['question.options'])
            ->get();

        $savedAnswers = StudentAnswer::where('attempt_id', $attempt->id)
            ->pluck('selected_option_id', 'question_id');

        return view('student.exam-attempt', compact('attempt', 'attemptQuestions', 'savedAnswers'));
    }

    /**
     * Async save answer via JSON request.
     */
    public function saveAnswer(Request $request, ExamAttempt $attempt)
    {
        $student = Auth::guard('student')->user();

        if ($attempt->student_id !== $student->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if ($attempt->status === 'submitted' || $attempt->isLocked() || $attempt->isExpired()) {
            return response()->json(['status' => 'error', 'message' => 'Exam attempt inactive'], 422);
        }

        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'selected_option_id' => 'required|exists:question_options,id',
        ]);

        StudentAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $request->question_id,
            ],
            [
                'selected_option_id' => $request->selected_option_id,
                'answered_at' => now(),
            ]
        );

        return response()->json(['status' => 'success']);
    }

    /**
     * Async record exam violation.
     */
    public function recordViolation(Request $request, ExamAttempt $attempt)
    {
        $student = Auth::guard('student')->user();

        if ($attempt->student_id !== $student->id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        $type = $request->input('violation_type', 'tab_hidden');

        ExamViolation::create([
            'attempt_id' => $attempt->id,
            'student_id' => $student->id,
            'exam_id' => $attempt->exam_id,
            'violation_type' => $type,
            'description' => "Terdeteksi meninggalkan halaman ujian (tab_hidden).",
            'occurred_at' => now(),
        ]);

        $attempt->increment('violation_count');
        $attempt->refresh();

        if ($attempt->violation_count >= 3) {
            $attempt->update(['status' => 'locked']);
            ActivityLog::record('STUDENT_LOCKED_VIOLATION', "Ujian siswa {$student->name} terkunci otomatis karena 3 kali pelanggaran tab switch.", null, $student);

            return response()->json([
                'status' => 'locked',
                'violation_count' => $attempt->violation_count,
            ]);
        }

        return response()->json([
            'status' => 'warning',
            'violation_count' => $attempt->violation_count,
        ]);
    }

    /**
     * Submit exam manually by student.
     */
    public function submitExam(Request $request, ExamAttempt $attempt)
    {
        $student = Auth::guard('student')->user();

        if ($attempt->student_id !== $student->id) {
            abort(403);
        }

        if ($attempt->status === 'submitted') {
            return redirect()->route('siswa.dashboard')->with('error', 'Ujian sudah diserahkan sebelumnya.');
        }

        return $this->processSubmission($attempt, false, $request);
    }

    /**
     * Internal server-side scoring & submission execution.
     */
    protected function processSubmission(ExamAttempt $attempt, bool $autoExpired = false, ?Request $request = null)
    {
        DB::transaction(function () use ($attempt, $request) {
            // Failsafe: Process answers submitted via request payload if any
            if ($request) {
                $submittedAnswers = [];
                if ($request->filled('answers_payload')) {
                    $decoded = json_decode($request->input('answers_payload'), true);
                    if (is_array($decoded)) {
                        $submittedAnswers = $decoded;
                    }
                } elseif ($request->filled('answers') && is_array($request->input('answers'))) {
                    $submittedAnswers = $request->input('answers');
                }

                foreach ($submittedAnswers as $qId => $optId) {
                    if ($qId && $optId) {
                        StudentAnswer::updateOrCreate(
                            [
                                'attempt_id' => $attempt->id,
                                'question_id' => $qId,
                            ],
                            [
                                'selected_option_id' => $optId,
                                'answered_at' => now(),
                            ]
                        );
                    }
                }
            }

            $exam = $attempt->exam;
            $questions = $exam->questions()->with('options')->get();
            $answers = StudentAnswer::where('attempt_id', $attempt->id)->get()->keyBy('question_id');

            $correctCount = 0;
            $wrongCount = 0;
            $totalPointsEarned = 0;
            $maxPossiblePoints = 0;

            foreach ($questions as $question) {
                $maxPossiblePoints += $question->points;
                $answer = $answers->get($question->id);

                if ($answer && $answer->selected_option_id) {
                    $selectedOption = $question->options->where('id', $answer->selected_option_id)->first();
                    if ($selectedOption && $selectedOption->is_correct) {
                        $correctCount++;
                        $totalPointsEarned += $question->points;
                    } else {
                        $wrongCount++;
                    }
                } else {
                    $wrongCount++;
                }
            }

            // Score formatted as whole number / integer (satuan tanpa koma)
            $finalScore = $maxPossiblePoints > 0 ? (int) round(($totalPointsEarned / $maxPossiblePoints) * 100) : 0;

            $attempt->update([
                'status' => 'submitted',
                'submitted_at' => now(),
                'score' => $finalScore,
                'correct_answers' => $correctCount,
                'wrong_answers' => $wrongCount,
            ]);

            ActivityLog::record(
                'STUDENT_EXAM_SUBMITTED',
                "Siswa {$attempt->student->name} menyelesaikan ujian '{$exam->title}' dengan nilai {$finalScore}.",
                null,
                $attempt->student
            );
        });

        if ($attempt->exam->show_result) {
            return redirect()->route('siswa.attempts.result', $attempt->id)->with('success', 'Ujian berhasil diselesaikan!');
        }

        return redirect()->route('siswa.dashboard')->with('success', 'Ujian berhasil diserahkan.');
    }

    /**
     * Show exam result view if allowed.
     */
    public function showResult(ExamAttempt $attempt)
    {
        $student = Auth::guard('student')->user();

        if ($attempt->student_id !== $student->id) {
            abort(403);
        }

        if (!$attempt->exam->show_result) {
            return redirect()->route('siswa.dashboard')->with('error', 'Hasil ujian ini disembunyikan oleh pengawas.');
        }

        $attempt->load(['exam.subject', 'exam.teacher', 'student.schoolClass']);

        return view('student.exam-result', compact('attempt'));
    }
}
