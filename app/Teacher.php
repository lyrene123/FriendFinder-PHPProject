<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Encapsulates the properties and behavior of a Teacher Model in the FriendFinder application.
 * Represents the Teacher entity of the database which can belong to many courses.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 * @package App
 */
class Teacher extends Model
{

    protected $fillable = [
        "name",
    ];

    /**
     * A teacher can have many courses. The CourseTeacher pivot also have day, start
     * and end as a column
     *
     * @return $this
     */
    public function courses(){
        return $this->belongsToMany("App\Course")
            ->using("App\CourseTeacher")->withPivot('day', 'start', 'end');
    }

    /**
     * If the parent is Course, return a CourseTeacher pivot
     *
     * @param Model $parent
     * @param array $attributes
     * @param string $table
     * @param bool $exists
     * @param null $using
     * @return CourseTeacher|\Illuminate\Database\Eloquent\Relations\Pivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        if($parent instanceof Course){
            return new CourseTeacher($attributes);
        }
        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }

}
