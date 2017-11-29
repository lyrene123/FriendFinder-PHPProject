<?php
namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class FriendRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $requests = $this->findRequests();

        return view('friend.request', ['requests' => $requests]);
    }

    private function findRequests(){
        return Friend::where("receiver_id", Auth::user()->id)
            ->where("confirmed", false)
            ->join("users", "friends.user_id", "=", "users.id")
            ->paginate(10);
    }

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

        return view('friend.request', ['requests' => $this->findRequests()]);
    }




}