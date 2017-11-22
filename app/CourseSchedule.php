<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseSchedule extends Pivot
{
    protected $table = 'course_schedules';
}
