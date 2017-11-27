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
        return $user->id === $friend->user_id;
    }
}
