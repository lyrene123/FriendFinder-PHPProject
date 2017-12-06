<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * Encapsulates the properties and behavior of a CourseTeacher Model in the FriendFinder application.
 * Represents the course_teacher entity of the database which is the bridging table between Courses and
 * Teachers.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 * @package App
 */
class CourseTeacher extends Pivot
{
    protected $fillable = ['day', 'start', 'end', 'teacher_id', 'course_id',];
}
