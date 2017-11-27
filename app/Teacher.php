<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    protected $fillable = [
        "name",
    ];

    public function courses(){
        return $this->belongsToMany("App\Course")->using("App\CourseSchedule");
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        if($parent instanceof Course){
            return new CourseTeacher($parent, $attributes, $table, $exists, $using);
        }
        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }

}
