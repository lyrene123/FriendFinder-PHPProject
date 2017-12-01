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

    /**
     * FriendBreakController constructor
     *
     * User must be authenticated to get into this page
     *
     * Initialize the default value of $days and $hours.
     * $days have only 5 days from 1 to 5
     * $hours start from 10am to 5pm
     */
    public function __construct() {
         $this->middleware('auth');
         $this->hours = ['1000', '1030', '1100', '1130', '1200', '1230', '1300'
             , '1330', '1400', '1430', '1500', '1530', '1600', '1630', '1700'];
         for($i=1; $i<=5; $i++) {
             $this->days[] = $i;
         }
    }

    /**
     * Show a view which only has the dropdown list for users to search
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
        $users = $this->findFriendsOnBreak($friends, $day, $start, $end);

        $users = $this->constructPagination($users, 10);
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
    private function findFriendsOnBreak($friends, $day, $start, $end) {
        $users = array();
        foreach($friends as $friend) {
            $userFriend = User::find($friend->receiver_id);
            $courses = $userFriend->courses()->get();
            $schedulesOfADay = $this->findSchedules($courses, $day, $start, $end);
            if ($this->isUserOnBreak($schedulesOfADay, $start, $end)) {
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
    private function isUserOnBreak($friendScheduleOfADay, $start, $end) {
        $prevEnd = 0;
        $firstCourse = $friendScheduleOfADay[0];
        foreach ($friendScheduleOfADay as $schedule) {
            $courseStart = $schedule->start;
            $courseEnd = $schedule->end;
            $diff = $courseStart - $prevEnd;
            $prevEnd = $courseEnd;
            if ($start >= $firstCourse->start && $end <= $courseEnd && $diff <= 0)
                return false;
        }
        return true;
    }

    /**
     * Find all the schedules that are close to start and end time. For example,
     * if start time is 1030, end time is 1200, and the courses of that day are
     * from 1000 to 1300, this will return that course order by the start time.
     *
     * @param $courses - array of Course object
     * @param $day - integer of day (1 - 5)
     * @return array of all the schedule order by start time
     */
    private function findSchedules($courses, $day, $start, $end) {
        $scheduleArray = array();
        foreach ($courses as $course) {
            $schedules = $course->teachers()
                ->where('course_teacher.day', '=', $day)
                ->where(function ($query) use ($start, $end){
                    $query->whereRaw('? between course_teacher.start and course_teacher.end', [$start])
                        ->orWhereRaw('? between course_teacher.start and course_teacher.end', [$end]);
                })->get();
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
        $entries = new LengthAwarePaginator($col->forPage($currentPage, $perPage), $col->count(), $perPage, $currentPage);
        $entries->setPath(LengthAwarePaginator::resolveCurrentPath());
        return $entries;
    }
}
