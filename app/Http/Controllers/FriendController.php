<?php

namespace App\Http\Controllers;

use App\User;
use App\Friend;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller class that manages all friends of each user of the FindFriend
 * web application. Handles displaying the list of friends of a logged in user
 * in the "Manage Friends" dashboard. Handles the deletion of a friendship
 * when logged in user clicks on the unfriend button on a specific friend.
 * Handles redirection and validation.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 * @package App\Http\Controllers
 */
class FriendController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the the 'Manage my friends' page dashboard by displaying a list
     * of friends with their status (Confirmed or Pending) and a unfriend
     * button for each friend entry in the table
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $friends =  User::find(Auth::user()->id)
            ->friends()
            ->join("users", "friends.receiver_id", "=", "users.id")
            ->paginate(10);

        return view('friend.index', ['friends' => $friends]);
    }

    /**
     * Deletes a friendship record(s) from the Friends table in the database.
     * Receives an input Friend model object and validates whether or not the
     * logged in user is authorized to delete the friendship record(s). If the
     * status of the friendship is confirmed, then two friendship records will
     * be removed from the table and otherwise, only one record will be removed.
     * When the deletion is completed, then the logged in user will be redirected
     * back to the "Manage Friends" page with a message.
     *
     * @param Friend $friend - Friend record to remove
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Friend $friend)
    {
        //proceed to deletion only if the input is not null
        if($friend !== null) {
            //check if logged in user is authorized to remove a friendship
            $this->authorize('destroy', $friend);

            //retrieve back the complete Friend record from the database to delete
            /*
             * NOTE: Must do this step because $friend is of type Friend by for some
             * reason the id of the $friend corresponds to the id of the User to whom
             * we want to end a friendship with, and not the id of the friend record
             * itself...
             * */
            $friend_record = User::find(Auth::user()->id)
                ->friends()
                ->where("receiver_id", $friend->id)
                ->first();
            $friend_record->delete();

            //retrieve the other corresponding record in the two way friendship if applicable
            $otherRecord = User::find($friend->id)
                ->friends()
                ->where("receiver_id", Auth::user()->id)
                ->first();

            //if no other record, then don't delete
            if($otherRecord !== null) {
                $otherRecord->delete();
            }

            //redirect with a message for the user
            $user_friend = User::find($friend->id);
            return Redirect::to('/friends')->with('messages', "$user_friend->firstname $user_friend->lastname is no longer your friend");
        }
        return redirect('friends');
    }
}
