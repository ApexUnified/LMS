<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'thumbnail',
        'thumbnail_public_id',
        'video',
        'video_public_id',
        'video_duration',
        'attachments',
        'is_published',
        'is_approved',
    ];

    protected $appends = ['lesson_progress'];

    // Relations
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function lessonProgress(): HasOne
    {
        return $this->hasOne(LessonProgress::class)->where('user_id', Auth::user()->id);
    }

    // Attributes
    public function getVideoDurationAttribute()
    {
        if (empty($this->attributes['video_duration'])) {
            return null;
        }

        $seconds = $this->attributes['video_duration'];
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }

    public function getlessonProgressAttribute()
    {
        return $this->lessonProgress()
            ->where('user_id', Auth::user()->id)
            ->first();
    }

    public function getDescriptionAttribute()
    {
        return ! empty($this->attributes['description']) ? json_decode($this->attributes['description']) : null;
    }

    protected $casts = [
        'attachments' => 'array',
    ];
}
