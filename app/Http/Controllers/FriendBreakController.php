<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class FriendBreakController extends Controller
{
    public function __construct() {
//        $this->middleware('auth');
    }

    public function index() {
        return view('friendbreak.index');
    }

    public function search(Request $request) {
        //$this->validate(); Need to do this validation

//        $user_id = Auth::user()->id;
        $friends = User::find(1)->friends()->get();

        $course_user = User::first()->courses()->first()->pivot;

//        $users = array();
//        foreach ($friends as $friend) {
//            $users[] = User::find($friend->receiver_id)->courses()->first()->pivot;
//        }
        return view('friendbreak.index', ['users' => $users]);
    }
}
