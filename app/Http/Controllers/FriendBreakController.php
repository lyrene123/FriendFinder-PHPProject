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

    /**
     * Find the friends who are on break on a given day, start and end time.
     * The value of the day must be from 1 to 5, and the value of the start
     * and end time must in the hours array.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
        $users = $this->findFrindsOnBreak($friends, $day, $start, $end);

        $users = $this->constructPagination($users, 1);
        return view('friendbreak.index', ['users' => $users]);
    }

    /**
     * Find all the friends who are on break on a given day with start and
     * end time
     *
     * @param array $friends
     * @param $day
     * @param $start
     * @param $end
     * @return array of user models who are on break
     */
    private function findFrindsOnBreak($friends, $day, $start, $end) {
        $users = array();
        foreach($friends as $friend) {
            $userFriend = User::find($friend->receiver_id);
            $courses = $userFriend->courses()->get();
            $schedulesOfADay = $this->findSchedulesOfADay($courses, $day);
            if ($this->checkIfUserIsOnBreak($schedulesOfADay, $start, $end)) {
                $users[$userFriend->id] = $userFriend;
            }
            unset($schedulesOfADay);
        }
        return $users;
    }

    /**
     * Check if the given friend's schedule of the day is available in
     * the given start and end time.
     *
     * @param array $friendScheduleOfADay
     * @param $start
     * @param $end
     * @return true if the friend is on break
     */
    private function checkIfUserIsOnBreak($friendScheduleOfADay, $start, $end) {
        $prevEnd = 0;
        foreach($friendScheduleOfADay as $schedule) {
            $courseStart = $schedule->start;
            $courseEnd = $schedule->end;
            $diff = $courseStart - $prevEnd;
            if($diff > 0 && $start < $courseStart && $end >= $courseStart) {
                return true;
            } else if($end > $courseEnd && $start >= $courseEnd) {
                return true;
            }
            $prevEnd = $courseEnd;
        }
        return false;
    }

    /**
     * Find all the schedules of a given day order by the start time.
     *
     * @param $courses - array of Course object
     * @param $day - integer of day (1 - 5)
     * @return array of all the schedule order by start time
     */
    private function findSchedulesOfADay($courses, $day) {
        $scheduleArray = array();
        foreach ($courses as $course) {
            $schedules = $course->teachers()->where('course_teacher.day', '=', $day)->get();
            foreach ($schedules as $schedule) {
                $scheduleArray[] = $schedule->pivot;
            }
        }

        // Sort by start
        usort($scheduleArray, function($a, $b) {
            return $a->start - $b->start;
        });

        return $scheduleArray;
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
