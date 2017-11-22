<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    protected $fillable = [
        "name",
    ];

//    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
//    {
//        if($parent instanceof Course){
//            return new CourseSchedule($parent, $attributes, $table, $exists, $using);
//        }
//        return parent::newPivot($parent, $attributes, $table, $exists, $using);
//    }

}
