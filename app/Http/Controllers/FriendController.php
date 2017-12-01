<?php

namespace App\Http\Controllers;

use App\User;
use App\Friend;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * Controller class that keeps track of the friends of each user of the FindFriend
 * web application. Handles returning the list of friends of a logged in user
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
     * Returns the list of all the friends of the logged in user
     * with their status (Confirmed or Pending)
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

            //delete other record only id it exists
            if($otherRecord !== null) {
                $otherRecord->delete();
            }

            //redirect with a message for the user
            $user_friend = User::find($friend->id);
            return Redirect::to('/friends')->with('messages', "$user_friend->firstname $user_friend->lastname is no longer your friend");
        }
        return redirect('friends');
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
    private function constructPagination($dataArr, $perPage){
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($dataArr);
        $currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $entries = new LengthAwarePaginator($currentPageSearchResults, count($col), $perPage);
        return $entries;
    }
}
