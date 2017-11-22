<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserEnrollment extends Pivot
{
    protected $table = 'user_enrollments';

    protected $fillable = ['sender_id', 'receiver_id',];

    public function users(){
        return $this->belongsToMany("App\User")->using("App\UserEnrollment");
    }


    public function courses() {
        return $this->belongsToMany('App\Course')->using('App\UserEnrollment');
    }
}
