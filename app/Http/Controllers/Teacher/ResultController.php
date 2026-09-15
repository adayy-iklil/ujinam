<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();

        $query = Exam::with(['subject', 'classes'])->withCount('attempts');
        if (!$user->isSuperadmin()) {
            $query->where('created_by', $user->id);
        }

        $exams = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('teacher.results.index', compact('exams'));
    }

    public function showExamResult(Exam $exam)
    {
        $this->authorizeExamAccess($exam);

        $attempts = ExamAttempt::where('exam_id', $exam->id)
            ->where('status', 'submitted')
            ->with(['student.schoolClass'])
            ->orderBy('score', 'desc')
            ->get();

        $highestScore = $attempts->max('score') ?? 0;
        $lowestScore = $attempts->min('score') ?? 0;
        $averageScore = $attempts->count() > 0 ? round($attempts->avg('score'), 2) : 0;

        return view('teacher.results.show', compact('exam', 'attempts', 'highestScore', 'lowestScore', 'averageScore'));
    }

    protected function authorizeExamAccess(Exam $exam)
    {
        $user = Auth::guard('web')->user();
        if (!$user->isSuperadmin() && $exam->created_by !== $user->id) {
            abort(403);
        }
    }
}
