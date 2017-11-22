<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseSchedule extends Pivot
{
    protected $table = 'course_schedules';

    protected $fillable = ['day', 'start', 'end', 'teacher_id', 'course_id',];


    public function courses(){
        return $this->belongsToMany("App\Course")->using("App\CourseSchedule");
    }


    public function teachers(){
        return $this->belongsToMany("App\Teacher")->using("App\CourseSchedule");
    }
}
