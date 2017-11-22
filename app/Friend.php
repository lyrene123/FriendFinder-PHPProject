<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Encapsulates the properties and behavior of a Friend Model in the FriendFinder application.
 * Represents the Friend entity of the database.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langloid
 * @package App
 */
class Friend extends Model
{
    protected $fillable = ['sender_id', 'receiver_id','confirmed',];
    protected $table = 'friends';

    /**
     * Associates a User with Friend entry.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users() {
        return $this->belongsTo('App\User');
    }

    /**
     * Checks if a User can unfriend current friend entry. A User cannot unfriend
     * someone if User has no relation whatsoever with that person in the Friend table.
     *
     * @param User $user a User instance to verify
     * @return bool true or false if input User can unfriend
     */
    public function userCanUnfriend(User $user){
        if($user->id === $this->sender_id || $user->id === $this->receiver_id){
            return true;
        }
        return false;
    }
}
