<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ["class", "section", "title",];
    
    public function users(){
        return $this->belongsToMany("App\User")->using("App\CourseUser");
    }

    public function teachers(){
        return $this->belongsToMany("App\Teacher")
            ->using("App\CourseTeacher")->withPivot('day', 'start', 'end');
    }

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
