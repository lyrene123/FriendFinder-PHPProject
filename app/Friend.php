<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = ['sender_id', 'receiver_id','confirmed',];
    protected $table = 'friends';

    public function users() {
        return $this->belongsTo('App\User');
    }

    public function userCanUnfriend(User $user){

        $userid
        //return $user->id === $this->u
    }
}
