<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseTeacher extends Pivot
{
    protected $fillable = ['day', 'start', 'end', 'teacher_id', 'course_id',];

}
