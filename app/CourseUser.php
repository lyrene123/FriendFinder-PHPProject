<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseUser extends Pivot
{

    protected $fillable = ['user_id', 'course_id',];

}
