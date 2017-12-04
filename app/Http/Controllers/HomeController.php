<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $friends = User::find(Auth::user()->id)
            ->friends()
            ->get();

        $result_friends = array();
        foreach($friends as $friend) {
            $item = array();
            $item['id'] = $friend->id;
            $item['confirmed'] = $friend->confirmed;
            $user = User::find($friend->receiver_id);
            $item['firstname'] = $user->firstname;
            $item['lastname'] = $user->lastname;
            $result_friends[] = $item;
        }

        $friends_paginated = $this->constructPagination($result_friends, 5);

        $pending = Friend::where("receiver_id", Auth:: user()->id)
            ->where("confirmed", false)
            ->join("users", "friends.user_id", "=", "users.id")
            ->get();


        $registered_courses = User::find(Auth::user()->id)
            ->where("users.id", "=", Auth::user()->id)
            ->join("course_user", "course_user.user_id", "=", "users.id")
            ->join("courses", "courses.id", "=", "course_user.course_id")
            ->get();

        $user = User::where("users.id", "=", Auth::user()->id)->get();

        return view('home', ['friends' => $friends_paginated, 'pending' => $pending, 'registered_courses' => $registered_courses, 'user' => $user,]);
    }

    /**
     * Handles pagination for a given data array, and with a number of records per page to display.
     * Code based on tutorial from Mahdi Hazaveh:
     * http://blog.hazaveh.net/2016/03/laravel-5-manual-pagination-from-array/
     *
     * @param $array An array of data to paginate.
     * @param $numRecords   The number of records to show, per page.
     * @return LengthAwarePaginator
     */
    private function constructPagination($array, $numRecords) {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($array);
        $perPage = $numRecords;
        $entries = new LengthAwarePaginator($col->forPage($currentPage, $perPage), $col->count(), $perPage, $currentPage);
        $entries->setPath(LengthAwarePaginator::resolveCurrentPath());
        return $entries;
    }
}
