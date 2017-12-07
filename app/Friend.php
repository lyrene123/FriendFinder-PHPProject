<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Encapsulates the properties and behavior of a Friend Model in the FriendFinder application.
 * Represents the Friend entity of the database which can belong to a single User record.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langloid
 * @package App
 */
class Friend extends Model
{
    protected $fillable = ['user_id', 'receiver_id','confirmed',];

    protected $table = 'friends';

    /**
     * Associates a User with Friend entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User');
    }
}
