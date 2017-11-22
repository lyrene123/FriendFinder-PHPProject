<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserEnrollment extends Pivot
{
    protected $table = 'users_enrollments';

    protected $fillable = ['user_id', 'course_id',];

    public function users(){
        return $this->belongsToMany("App\User")->using("App\UserEnrollment");
    }


    public function courses() {
        return $this->belongsToMany('App\Course')->using('App\UserEnrollment');
    }
}
