<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamViolation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();

        // Get query depending on role
        $examsQuery = Exam::query();
        if (!$user->isSuperadmin()) {
            $examsQuery->where('created_by', $user->id);
        }

        $totalExams = (clone $examsQuery)->count();
        $activeExams = (clone $examsQuery)->whereIn('status', ['published', 'ongoing'])->where('is_active', true)->count();
        
        $examIds = (clone $examsQuery)->pluck('id');

        $activeAttemptsCount = ExamAttempt::whereIn('exam_id', $examIds)
            ->where('status', 'in_progress')
            ->count();

        $lockedCount = ExamAttempt::whereIn('exam_id', $examIds)
            ->where(function ($q) {
                $q->where('status', 'locked')->orWhere('violation_count', '>=', 3);
            })
            ->count();

        $recentExams = (clone $examsQuery)->with(['subject', 'classes'])
            ->withCount(['attempts', 'questions'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('teacher.dashboard', compact('totalExams', 'activeExams', 'activeAttemptsCount', 'lockedCount', 'recentExams'));
    }
}
