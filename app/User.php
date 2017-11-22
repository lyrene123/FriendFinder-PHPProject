<?php

namespace App;

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

    public function courses() {
        return $this->belongsToMany('App\Course')->using('App\UserEnrollment');
    }

    public function newPivot(self $parent, array $attributes, $table, $exists, $using = null)
    {
        if($parent instanceof Course){
            return new UserEnrollment($parent, $attributes, $table, $exists);
        }
        return parent::newPivot($parent, $attributes, $table, $exists);
    }
}
