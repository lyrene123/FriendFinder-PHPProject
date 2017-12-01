<?php

namespace App\Http\Controllers;


use App\Friend;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller class that seaching of other user by the logged in user of in the FindFriend
 * web application. Handles returning the results of the search back to the view.
 * Handles adding a new friend for the logged in user clicking on the Add Friend button
 * on a specific user who is part of a search result.
 * Handles redirection and validation.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 * @package App\Http\Controllers
 */
class SearchFriendController extends Controller
{
    /**
     * SearchFriendController constructor.
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Returns the view of the search page in order to display the input form
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('friend.search');
    }

    /**
     * Handles the search action of the logged in user when clicking on the search
     * button of the input form and submitting the form. Retrieves the input of the
     * logged in user and validates it. Searches in the database for users who has
     * an approximate match to the search criteria of the logged in user.
     *
     * Returns a list of users including information whether or not they are already
     * friends with the logged in user.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function search(Request $request)
    {
        //validate the input
        $this->validate($request, [
            'fname' => 'required_without_all:lname',
            'lname' => 'required_without_all:fname',
        ], [
            'required_without_all' => 'Please enter a :attribute',
        ]);

        //put an empty string if no first name input provided
        $fname = $request->input("fname");

        //put an empty string if no last name input provided
        $lname = $request->input("lname");

        //search for matches
        $users = User::where('firstname', 'like', "%$fname%")
            ->where('lastname', 'like', "%$lname%")
            ->get();

        //construct the array result containing the list of users and boolean whether or not they are friends
        $usersArr = $this->constructSearchResultArr($users);

        //construct the pagination
        $users_paginated = $this->constructPagination($usersArr);
        return view('friend.search', ['users' => $users_paginated]);
    }

    /**
     * Handles the accept action of the logged in user when clicking on the Add Friend
     * button for a specific user in the search result.
     * Verifies first if the logged in user is authorized to add the new user as a friend
     * Redirects to the friends page with a validation message for the user
     *
     * @param User $user
     * @return mixed
     */
    public function add(User $user){
        if($user !== null ){
            $this->authorize('add', $user);
            $found = Friend::firstOrCreate([
                'user_id' => Auth::user()->id,
                'receiver_id' => $user->id,
                'confirmed' => false,
            ]);
        }
        return Redirect::to('/friends')->with('messages', "A request is sent to $user->firstname $user->lastname");
    }

    /**
     * Helper method to construct the search result array that will contain the
     * Users objects and a boolean whether or not a user is already friends with
     * the logged in user
     *
     * @param $users
     * @return array
     */
    private function constructSearchResultArr($users){
        $usersArr = array();

        //loop through the users object matching the search criteria
        for($i = 0; $i < count($users); $i = $i + 1){
            $item = array();
            $item['user'] =  $users[$i]; //add a User object

            //check if the user is already friends or not
            $found = User::find(Auth::user()->id)
                ->friends()
                ->where('receiver_id', $users[$i]->id)
                ->first();

            //add a boolean if user is friends or not
            if($found){
                $item['isFriends'] = true;
            } else {
                $item['isFriends'] = false;
            }

            //add the combination user-boolean
            $usersArr[$i] = $item;
        }
        return $usersArr;
    }

    /**
     * Creates Pagination for a given data array and with a number of records
     * per page to display
     *
     * Solution based on
     * http://blog.hazaveh.net/2016/03/laravel-5-manual-pagination-from-array/
     *
     * @param $dataArr the array of data
     * @param $perPage the number of data to show per page
     * @return LengthAwarePaginator Pagination object
     */
    private function constructPagination($dataArr){
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($dataArr);
        $perPage = 2;
        $entries = new LengthAwarePaginator($col->forPage($currentPage, $perPage), $col->count(), $perPage, $currentPage);
        $entries->setPath(LengthAwarePaginator::resolveCurrentPath());
        return $entries;
    }
}