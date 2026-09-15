<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'created_by',
        'title',
        'description',
        'instructions',
        'duration_minutes',
        'start_at',
        'end_at',
        'status',
        'show_result',
        'randomize_questions',
        'randomize_options',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'show_result' => 'boolean',
            'randomize_questions' => 'boolean',
            'randomize_options' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'exam_classes', 'exam_id', 'class_id')->withTimestamps();
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('question_order', 'asc');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function violations(): HasMany
    {
        return $this->hasMany(ExamViolation::class);
    }

    public function isAvailableNow(): bool
    {
        $now = now();
        return $this->is_active && in_array($this->status, ['published', 'ongoing']) && $now->between($this->start_at, $this->end_at);
    }
}
