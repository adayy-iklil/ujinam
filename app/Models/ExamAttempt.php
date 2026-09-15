<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'started_at',
        'submitted_at',
        'expires_at',
        'status',
        'score',
        'correct_answers',
        'wrong_answers',
        'violation_count',
        'force_logged_out',
        'reset_by',
        'reset_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
            'expires_at' => 'datetime',
            'reset_at' => 'datetime',
            'force_logged_out' => 'boolean',
        ];
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function resettedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reset_by');
    }

    public function attemptQuestions(): HasMany
    {
        return $this->hasMany(ExamAttemptQuestion::class, 'attempt_id')->orderBy('question_order', 'asc');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(StudentAnswer::class, 'attempt_id');
    }

    public function violations(): HasMany
    {
        return $this->hasMany(ExamViolation::class, 'attempt_id')->orderBy('occurred_at', 'desc');
    }

    public function isExpired(): bool
    {
        return now()->greaterThanOrEqualTo($this->expires_at);
    }

    public function isLocked(): bool
    {
        return $this->status === 'locked' || $this->violation_count >= 3;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'submitted';
    }
}
