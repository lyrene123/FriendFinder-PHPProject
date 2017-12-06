<?php

namespace App\Policies;

use App\User;
use App\Course;
use App\CourseUser;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class CourseManagerPolicy
 * Policy class for managing the courses of the logged in user.
 * Handles validating the action of the logged in user related to the
 * courses the user is registered for or for new courses to be added.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 * @package App\Policies
 */
class CourseManagerPolicy
{
    use HandlesAuthorization;

    /**
     * CourseManagerPolicy constructor.
     * Default constructor to instantiate a CourseManagerPolicy.
     *
     */
    public function __construct()
    {

    }

    /**
     * Policy for dropping a course.
     * A user can only drop a course that he/she is registered for.
     *
     * @param User $user The logged in user
     * @param Course $course The course to be dropped
     * @return bool true or false whether or not user can drop the course
     */
    public function drop(User $user, Course $course){
        //retrieve the course_user record of the logged in user and the specified course
        $registered_course = CourseUser::select('id')
            ->where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->get();
        //if a record is found then user is registered in the specified course
        if(isset($registered_course) && count($registered_course) === 1){
            return true;
        }
        return false;
    }
}
