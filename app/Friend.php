<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    protected $fillable = ['status',];

    public function users() {
        return $this->belongsTo('App\User');
    }
}
