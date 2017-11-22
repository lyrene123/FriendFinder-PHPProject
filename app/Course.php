<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ["class", "section", "title",];

    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        if($parent instanceof Teacher){
            return new CourseSchedule($parent, $attributes, $table, $exists, $using);
        }

        if($parent instanceof User) {
            return new UserEnrollment($parent, $attributes, $table, $exists, $using);
        }

        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }


}
