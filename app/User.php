<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'program', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function friends() {
        return $this->hasMany('App\Friend');
    }

    /**
     * A user can have many coourse
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses() {
        return $this->belongsToMany('App\Course')
            ->withPivot('user_id', 'course_id')->withTimestamps();
    }

    /**
     * If the parent is course, return a CourseUser pivot
     *
     * @param Model $parent
     * @param array $attributes
     * @param string $table
     * @param bool $exists
     * @param null $using
     * @return CourseUser|\Illuminate\Database\Eloquent\Relations\Pivot
     */
    public function newPivot(Model $parent, array $attributes, $table, $exists, $using = null)
    {
        if($parent instanceof Course){
            return new CourseUser($attributes);
        }
        return parent::newPivot($parent, $attributes, $table, $exists, $using);
    }

}
