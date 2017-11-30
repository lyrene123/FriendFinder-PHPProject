<?php

namespace App\Policies;

use App\User;
use App\Course;
use App\CourseUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class CourseManagerPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {

    }

    public function drop(User $user, Course $course){

        $registered_course = CourseUser::select('id')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id);

        if(isset($registered_course) && count($registered_course) === 1){
            return true;
        }
        return false;
    }
}
