<?php

namespace App\Policies;

use App\User;
use App\Friend;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

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
     * @param User $user
     * @param Friend $friend
     * @return bool
     */
    public function destroy(User $user, Friend $friend)
    {
        //can only unfriend someone if that person is your friend.
        //this must return true
        $found = User::find($user->id)
            ->friends()
            ->where('receiver_id', $friend->receiver_id)
            ->first();

        //cannot unfriend yourself
        //this must return false
        $isYourself = $user->id === $friend->receiver_id;

        //must be who you say you are
        //this must be true
        $isAuthenticated = $user->id === $friend->user_id;

        return $found && !$isYourself && $isAuthenticated;
    }

    public function add(User $user, User $userToAdd)
    {
        //can only add a friend that's not your friend
        //this must return false
        $found = User::find($user->id)
            ->friends()
            ->where('receiver_id', $userToAdd->id)
            ->first();

        //cannot add yourself as a friend
        //this must return false
        $isYourself = $user->id === $userToAdd->id;

        return !$found && !$isYourself;
    }

    public function accept(User $user, User $userRequesting){

        //can only accept a request existing in the db and that is intended for you
        //this must return a record if valid
        $found = Friend::where("user_id", $userRequesting->id)
            ->where("receiver_id", Auth::user()->id)
            ->first();

        //returns true if valid
        return $found !== null;
    }

    public function decline(User $user, Friend $friend){

    }
}
