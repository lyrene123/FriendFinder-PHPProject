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
        $userid = Auth::user()->id;

        $friends = User::find($userid)
            ->friends()
            ->join("users", "friends.receiver_id", "=", "users.id")
            ->paginate(10);

        return view('friend.index', ['friends' => $friends]);
    }

    public function destroy(Friend $friend)
    {
        $this->authorize('destroy', $friend);


       // $record = DB::table('friends')
        $friend->delete();

        if($friend->confirmed) {
            $otherRecord = User::find($friend->receiver_id)
                ->friends()
                ->where("user_id", $friend->receiver_id)
                ->where("receiver_id", Auth::user()->id)
                ->get();
            $otherRecord[0]->delete();
        }
        return redirect('friends');
    }

    public function store(Request $request)
    {
        //TODO - validate if already friends then add an friend entry
    }
}
