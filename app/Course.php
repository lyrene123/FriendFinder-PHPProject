<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ["class", "title"];

    public function newPivot(self $parent, array $attributes, $table, $exists, $using = null)
    {
        if($parent instanceof Teacher){
            return new CourseSchedule($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }

    public function teachers(){
        return $this->belongsToMany("App\Course")->using("App\CourseSchedule");
    }

    public function users(){
        return $this->belongsToMany("App\User")->using("App");
    }
}
