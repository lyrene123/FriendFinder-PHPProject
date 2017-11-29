<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    protected $fillable = [
        "name",
    ];

    public function courses(){
        return $this->belongsToMany("App\Course")
            ->using("App\CourseTeacher")->withPivot('day', 'start', 'end');
    }

    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        if($parent instanceof Course){
            return new CourseTeacher($attributes);
        }
        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }

}
