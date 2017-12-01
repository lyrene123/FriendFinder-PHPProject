<?php
namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * Controller class that keeps track of the friend requests of each user of the FindFriend
 * web application. Handles returning the list of requests of a logged in user
 * in the "View your requests" dashboard. Handles the accept and decline actions of a logged in user
 * when logged in user clicks on the accept or decline button on a specific request.
 * Handles redirection and validation.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 * @package App\Http\Controllers
 */
class FriendRequestController extends Controller
{
    /**
     * FriendRequestController constructor.
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Returns the list of all requests of the logged in user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $requests = Friend::where("receiver_id", Auth::user()->id)
            ->where("confirmed", false)
            ->join("users", "friends.user_id", "=", "users.id")
            ->paginate(10);

        return view('friend.request', ['requests' => $requests]);
    }

    /**
     * Handles the logged in user's action of accepting a friend request.
     * Verifies first that the logged in user is authorized to accept a
     * friend request. Updates the related friend record existing already in the
     * Friend table and sets the confirmed to true. Adds another friend record
     * to the table. Redirects back to the Requests page
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function accept(User $user)
    {
        $this->authorize("accept", $user);

        //retrieve the existing records to update confirmed true
        $record = Friend::where("user_id", $user->id)
            ->where("receiver_id", Auth::user()->id)
            ->first();
        $record->confirmed = true;
        $record->save();

        //create second friend record
        Friend::firstOrCreate([
            'user_id' => Auth::user()->id,
            'receiver_id' => $user->id,
            'confirmed' => true,
        ]);

        return redirect('requests');
    }

    /**
     * Handles the logged in user's action of declining a friend request.
     * Verifies first that the logged in user is authorized to decline a
     * friend request. Deletes the related friend record already existing
     * in the Friend table.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function decline(User $user)
    {
        $this->authorize("decline", $user);

        $record = Friend::where("user_id", $user->id)
            ->where("receiver_id", Auth::user()->id)
            ->first();
        $record->delete();

        return redirect('requests');
    }




}