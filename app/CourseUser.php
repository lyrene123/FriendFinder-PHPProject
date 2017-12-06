<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Encapsulates the properties and behavior of a CourseUser Model in the FriendFinder application.
 * Represents the course_user entity of the database which is the bridging table between Courses and
 * Users.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 * @package App
 */
class CourseUser extends Pivot
{

    protected $fillable = ['user_id', 'course_id',];

}
