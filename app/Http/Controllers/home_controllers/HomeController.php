<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * HomeController defines the controller for the FriendFinder site's default / home page. This controller will retrieve
 * all data from the database necessary to display on the home page, and send it to the view. The page is only
 * accessible to authenticated users.
 *
 * @author Peter Bellefleur
 * @author Lyrene Labor
 * @author Philippe Langlois
 * @author Pengkim Sy
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance. Controller attaches authorization middleware, to prevent unauthenticated users
     * from accessing this page.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Retrieve data from database, and show the application dashboard. Retrieves a list of courses the user has
     * registered for, a list of friends, and a list of pending friend requests using the related models.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get list of all user friends
        $friends = User::find(Auth::user()->id)
            ->friends()
            ->get();

        //break friends list into 2D array
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

        //paginate array of friends
        $friends_paginated = $this->constructPagination($result_friends, 5);

        //get list of incoming, pending friend requests for user
        $pending = Friend::where("receiver_id", Auth:: user()->id)
            ->where("confirmed", false)
            ->join("users", "friends.user_id", "=", "users.id")
            ->get();

        //get list of registered courses for user
        $registered_courses = User::find(Auth::user()->id)
            ->courses()
            ->get();

        $user = User::find(Auth::user()->id);
        //send all retrieved data to the view
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
