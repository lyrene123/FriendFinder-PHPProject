<?php

namespace App\Http\Controllers;

use App\User;
use App\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller class that manages all friends of each user of the FindFriend
 * web application. Handles displaying the list of friends of a logged in user.
 *
 * TODO: add more detail to javadocs once class is  more or less done
 *
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
     * button for only confirmed friends.
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

    public function destroy(Friend $friend)
    {
        if($friend !== null) {
            $friend_record = User::find(Auth::user()->id)
                ->friends()
                ->where("receiver_id", $friend->id)
                ->first();
            if($friend_record !== null) {
                $this->authorize('destroy', $friend_record);

                $friend_record->delete();

                if (!$friend->confirmed) {
                    $otherRecord = User::find($friend->id)
                        ->friends()
                        ->where("receiver_id", Auth::user()->id)
                        ->first();
                    $otherRecord->delete();
                }
            }
        }
        return redirect('friends');
    }


    public function store(Request $request)
    {
        //TODO - validate if already friends then add an friend entry
    }
}
