<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\CourseUser;

class Course extends Model
{
    protected $fillable = ["class", "section", "title",];

    /**
     * A course can have many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(){
        return $this->belongsToMany("App\User")->using("App\CourseUser");
    }

    /**
     * A course can have many teachers
     *
     * @return $this
     */
    public function teachers(){
        return $this->belongsToMany("App\Teacher")
            ->using("App\CourseTeacher")->withPivot('day', 'start', 'end');
    }

    /**
     * If the parent is Teacher, return CourseTeacher pivot.
     * If the parent is User, return CourseUer pivot.
     *
     * @param Model $parent
     * @param array $attributes
     * @param string $table
     * @param bool $exists
     * @param null $using
     * @return CourseTeacher|\App\CourseUser|\Illuminate\Database\Eloquent\Relations\Pivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        if($parent instanceof Teacher){
            return new CourseTeacher($attributes);
        }

        if($parent instanceof User) {
            return new CourseUser($attributes);
        }

        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }
}
