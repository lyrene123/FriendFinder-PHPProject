<?php

namespace App\Http\Controllers;


use App\Friend;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class SearchFriendController extends Controller
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

    public function index()
    {
        return view('friend.search');
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'fname' => 'required_without_all:lname',
            'lname' => 'required_without_all:fname',
        ], [
            'required_without_all' => 'Please enter at least a first name or last name',
        ]);

        $fname = $request->input("fname");
        if($fname === null){
            $fname = "";
        }

        $lname = $request->input("lname");
        if($lname === null){
            $lname = "";
        }

        $users = User::where('firstname', 'like', "%$fname%")
            ->where('lastname', 'like', "%$lname%")
            ->paginate(10);

        $usersArr = array();
        for($i = 0; $i < count($users); $i = $i + 1){
            $item = array();
            $item['user'] =  $users[$i];
            $found = User::find(Auth::user()->id)
                ->friends()
                ->where('receiver_id', $users[$i]->id)
                ->first();
            if($found){
                $item['isFriends'] = true;
            } else {
                $item['isFriends'] = false;
            }
            $usersArr[$i] = $item;
        }

        $users_paginated = $this->constructPagination($usersArr);
        return view('friend.search', ['users' => $users_paginated]);
    }

    public function add(User $user){
        if($user !== null ){
            $this->authorize('add', $user);
            $found = Friend::firstOrCreate([
                'user_id' => Auth::user()->id,
                'receiver_id' => $user->id,
                'confirmed' => false,
            ]);
        }
        return redirect('friends');
    }

    //http://blog.hazaveh.net/2016/03/laravel-5-manual-pagination-from-array/
    private function constructPagination($dataArr){
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($dataArr);
        $perPage = 5;
        $currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $entries = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);
        return $entries;
    }
}