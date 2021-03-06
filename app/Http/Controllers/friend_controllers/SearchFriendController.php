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
 * Controller class that searching of other user by the logged in user of in the FindFriend
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

        $fname = $request->input("fname");
        $lname = $request->input("lname");

        //search for matches
        $users = User::where('firstname', 'ILIKE', "%$fname%")
            ->where('lastname', 'ILIKE', "%$lname%")
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
     * Redirects to the friends page with a validation message for the user if new friend added
     * Redirects to the friends page with another message if the user is already friends with logged in user
     *
     * @param User $user
     * @return mixed
     */
    public function add(User $user){
        if($user !== null ){
            $this->authorize('add', $user);
            if($this->isFriends($user)){
                return Redirect::to('/friends')->with('messages', "$user->firstname $user->lastname is already your friend 
                or has sent you a friend request already");
            } else {
                $found = Friend::firstOrCreate([
                    'user_id' => Auth::user()->id,
                    'receiver_id' => $user->id,
                    'confirmed' => false,
                ]);
                return Redirect::to('/friends')->with('messages', "A request is sent to $user->firstname $user->lastname");
            }
        }

    }

    /**
     * Verifies if an input user is already friends with the logged in user.
     *
     * @param User $user User to check if already friends with logged in user
     * @return bool true or false whether already friends
     */
    private function isFriends(User $user){
        $isAlreadyFriends1 = User::find(Auth::user()->id)
            ->friends()
            ->where('receiver_id', $user->id)
            ->first();
        $isAlreadyFriends2 = User::find($user->id)
            ->friends()
            ->where('receiver_id', Auth::user()->id)
            ->first();
        if(isset($isAlreadyFriends1) || isset($isAlreadyFriends2)){
            return true;
        }
        return false;
    }

    /**
     * Helper method to construct the search result array that will contain the
     * User's first and last name, id, program and a boolean whether or not a user is already friends with
     * the logged in user
     *
     * @param $users list of users
     * @return array containing specific information of each users
     */
    private function constructSearchResultArr($users){
        $usersArr = array();

        //loop through the users object matching the search criteria
        for($i = 0; $i < count($users); $i = $i + 1){
            $item = array();
            $item['firstname'] =  $users[$i]->firstname;
            $item['lastname'] =  $users[$i]->lastname;
            $item['program'] =  $users[$i]->program;
            $item['id'] =  $users[$i]->id;

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

            //add item array
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
        $perPage = 10;
        $entries = new LengthAwarePaginator($col->forPage($currentPage, $perPage), $col->count(), $perPage, $currentPage);
        $entries->setPath(LengthAwarePaginator::resolveCurrentPath());
        return $entries;
    }
}