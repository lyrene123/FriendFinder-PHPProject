<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = ['sender_id', 'receiver_id','confirmed',];

    public function users() {
        return $this->belongsTo('App\User');
    }
}
