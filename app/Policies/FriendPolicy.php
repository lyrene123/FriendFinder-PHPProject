<?php

namespace App\Policies;

use App\User;
use App\Friend;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy class that handles all verification for all tasks
 * related to Friends such as unfriending, adding a friend,
 * accepting a friend request, declining a friend request.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 * @package App\Policies
 */
class FriendPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Verifies if the deletion of a Friend record
     * is authorized for the logged in user. The logged in user
     * can only unfriend someone if that person is a friend.
     *
     * @param User $user Logged in user
     * @param Friend $friend Friend record to delete
     * @return bool true or false if authorized
     */
    public function destroy(User $user, Friend $friend)
    {
        //can only unfriend someone if that person is your friend.
        //this must return a record to be valid
        $found = $user->friends()
            ->where('receiver_id', $friend->receiver_id)
            ->first();

        return $found !== null;
    }

    /**
     * Verifies if the adding of a a new user as a friend
     * is authorized for the logged in user.
     * A logged in user cannot add themselves as a friend.
     *
     * @param User $user Logged in user
     * @param User $userToAdd User to add as friend
     * @return bool boolean if authorized
     */
    public function add(User $user, User $userToAdd)
    {
        //cannot add yourself as a friend
        //this must return true to be valid
        $isYourself = $user->id !== $userToAdd->id;

        return $isYourself;
    }

    /**
     * Verifies if the accepting of a friend request
     * is authorized for the logged in user. The logged in user
     * can only accept a request existing in the db and that is intended for you.
     *
     * @param User $user Logged in user
     * @param User $userRequesting User to add as friend
     * @return bool boolean if authorized
     */
    public function accept(User $user, User $userRequesting){
        //can only accept a request existing in the db and that is intended for you
        //returns true if valid
        return $this->retrieveRequest($user, $userRequesting);
    }

    /**
     * Returns the a specific friend record from the Friends table
     * based on a user_id and a receiver_id
     *
     * @param User $user Logged in user
     * @param User $userRequesting User to add as friend
     * @return bool boolean if authorized
     */
    private function retrieveRequest(User $user, User $userRequesting){
        $found = Friend::where("user_id", $userRequesting->id)
            ->where("receiver_id", $user->id)
            ->first();

        //returns true if valid
        return $found !== null;
    }

    /**
     * Verifies if the declining of a friend request
     * is authorized for the logged in user. The logged in user
     * can only decline a request existing in the db and that is intended for you.
     *
     * @param User $user Logged in user
     * @param User $userRequesting User to add as friend
     * @return bool boolean if authorized
     */
    public function decline(User $user, User $userRequesting){
        //can only decline a request existing in the db and that is intended for you
        //returns true if valid
        return $this->retrieveRequest($user, $userRequesting);
    }
}
