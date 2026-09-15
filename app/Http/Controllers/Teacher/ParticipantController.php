<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    /**
     * Live exam participants monitoring page.
     */
    public function index(Exam $exam)
    {
        $this->authorizeExamAccess($exam);

        $exam->load('classes');
        $classIds = $exam->classes->pluck('id');

        $attempts = ExamAttempt::where('exam_id', $exam->id)
            ->with(['student.schoolClass', 'violations'])
            ->orderBy('started_at', 'desc')
            ->get();

        return view('teacher.participants.index', compact('exam', 'attempts'));
    }

    /**
     * Overall violations monitoring list.
     */
    public function indexViolations()
    {
        $user = Auth::guard('web')->user();

        $query = ExamViolation::with(['student.schoolClass', 'exam', 'attempt']);
        if (!$user->isSuperadmin()) {
            $query->whereHas('exam', function ($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        $violations = $query->orderBy('occurred_at', 'desc')->paginate(20);

        return view('teacher.violations.index', compact('violations'));
    }

    /**
     * Teacher Action: Reset Attempt / Unlock Student.
     */
    public function resetAttempt(Request $request, ExamAttempt $attempt)
    {
        $this->authorizeExamAccess($attempt->exam);
        $user = Auth::guard('web')->user();

        $actionType = $request->input('action_type', 'unlock'); // 'unlock' or 'full_reset'

        if ($actionType === 'full_reset') {
            // Full reset: delete answers and reset attempt state
            $attempt->answers()->delete();
            $attempt->update([
                'status' => 'in_progress',
                'submitted_at' => null,
                'score' => null,
                'correct_answers' => null,
                'wrong_answers' => null,
                'violation_count' => 0,
                'reset_by' => $user->id,
                'reset_at' => now(),
            ]);

            ActivityLog::record(
                'TEACHER_RESET_ATTEMPT',
                "Guru {$user->name} melakukan reset penuh sesi ujian untuk siswa {$attempt->student->name} (NIS: {$attempt->student->nis}).",
                $user
            );

            return back()->with('success', "Sesi ujian siswa {$attempt->student->name} berhasil di-reset sepenuhnya.");
        } else {
            // Unlock & reset violation counter
            $attempt->update([
                'status' => 'in_progress',
                'violation_count' => 0,
                'reset_by' => $user->id,
                'reset_at' => now(),
            ]);

            ActivityLog::record(
                'TEACHER_UNLOCK_STUDENT',
                "Guru {$user->name} membuka kuncian (unlock) & mereset pelanggaran untuk siswa {$attempt->student->name}.",
                $user
            );

            return back()->with('success', "Siswa {$attempt->student->name} berhasil di-unlock dan dapat melanjutkan ujian.");
        }
    }

    protected function authorizeExamAccess(Exam $exam)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isSuperadmin() && $exam->created_by !== $user->id) {
            abort(403);
        }
    }
}
