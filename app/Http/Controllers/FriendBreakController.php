<?php

namespace App\Http\Controllers;

use App\CourseTeacher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

class FriendBreakController extends Controller
{
    private $hours;
    private $days;

    public function __construct() {
        $this->middleware('auth');
         $this->hours = ['1000', '1030', '1100', '1130'
            , '1200', '1230', '1300', '1330', '1400', '1430', '1500', '1530', '1600'
            , '1630', '1700'];
         for($i=1; $i<=5; $i++) {
             $this->days[] = $i;
         }
    }

    public function index() {
        return view('friendbreak.index');
    }

    public function search(Request $request) {
        $day = $request->input('day');
        $start = $request->input('start');
        $end = $request->input('end');

        $request->validate([
            'day' => [
                'required',
                Rule::in($this->days)
            ],
            'start' => [
                'required',
                Rule::in($this->hours)
            ],
            'end' => [
                'required',
                Rule::in($this->hours)
            ]
        ],
        [
            'required' => 'Please select :attribute!',
            'in' => 'Please select the one value from the :attribute drop down'
        ]);

        $user = Auth::user();
        $friends = $user->friends()->where('confirmed', true)->get();
        $users = array();

        foreach($friends as $friend) {
            $scheduleArray = array();
            $userFriend = User::find($friend->receiver_id);
            $courses = $userFriend->courses()->get();
            foreach ($courses as $course) {
                $schedules = $course->teachers()->where('course_teacher.day', '=', $day)->get();
                foreach ($schedules as $schedule) {
                    $scheduleArray[] = $schedule->pivot;
                }
            }

            // Sort by start
            uasort($scheduleArray, function($a, $b) {
                return $a->start - $b->start;
            });

            $prevEnd = 0;
            foreach($scheduleArray as $schedule) {
                $courseStart = $schedule->start;
                $courseEnd = $schedule->end;
                $diff = $courseStart - $prevEnd;
                if($diff > 0 && $start < $courseStart) {
                    $users[$userFriend->id] = $userFriend;
                } else if($end > $courseEnd && $start >= $courseEnd) {
                    $users[$userFriend->id] = $userFriend;
                }
                $prevEnd = $courseEnd;
            }
            unset($scheduleArray);
        }

//        $users = $this->constructPagination($users, 1);
        return view('friendbreak.index', ['users' => $users]);
    }

    /**
     * Get this array paginated method from
     * http://blog.hazaveh.net/2016/03/laravel-5-manual-pagination-from-array/
     *
     * @param $dataArr
     * @param $perPage
     * @return LengthAwarePaginator
     */
    private function constructPagination($dataArr, $perPage){
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($dataArr);
        $currentPageSearchResults = $col->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $entries = new LengthAwarePaginator($currentPageSearchResults, count($col)
            , $perPage, $currentPage, ['path' => LengthAwarePaginator::resolveCurrentPath()]);
        return $entries;
    }
}
