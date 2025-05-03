<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;

class Enrollment extends Model
{
    protected $fillable = ['user_id', 'course_id', 'progress'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class)->withTimestamps();
    }

    public function lessonProgresses()
    {
        return $this->hasMany(LessonProgress::class);
    }
}
