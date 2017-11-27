<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ["class", "section", "title",];
    
    public function users(){
        return $this->belongsToMany("App\User")->using("App\UserEnrollment");
    }

    public function teachers(){
        return $this->belongsToMany("App\Teacher")->using("App\CourseSchedule");
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        if($parent instanceof Teacher){
            return new CourseTeacher($parent, $attributes, $table, $exists, $using);
        }

        if($parent instanceof User) {
            return new CourseUser($parent, $attributes, $table, $exists, $using);
        }

        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }


}
