<?php

namespace App\Http\Controllers;

use App\CourseTeacher;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class FriendBreakController extends Controller
{
    private $hours;
    private $days;

    public function __construct() {
//        $this->middleware('auth');
         $this->hours = [800, 830, 900, 930, 1000, 1030, 1100, 1130
            , 1200, 1230, 1300, 1330, 1400, 1430, 1500, 1530, 1600
            , 1630, 1700, 1730, 1800, 1830, 1900, 1930, 2000, 2030
            , 2100, 2130];
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

        $this->validate($request, [
            'day' => "required|in_array:$this->days",
            'start' => "required|in_array:$this->hours",
            'end' => "required|min:$start|in_array:$this->hours"
        ]);

//        $user = Auth::user();
        $user = User::first();
        $friends = $user->friends()->get();

        $users = array();
        $scheduleArray = array();
        foreach($friends as $friend) {
            $userFriend = User::find($friend->receiver_id);
            $courses = $userFriend->courses()->get();
            foreach ($courses as $course) {
                $schedules = $course->teachers()->where('course_teacher.day', '=', $day)->get();
                foreach ($schedules as $schedule) {
                    $scheduleArray[$userFriend->id] = $schedule->pivot;
                }
            }
        }

        // Sort by start
        uasort($scheduleArray, function($a, $b) {
           return $a->start - $b->start;
        });

        $prevEnd = 0;
        foreach($scheduleArray as $id => $schedule) {
            $courseStart = $schedule->start;
            $courseEnd = $schedule->end;
            $diff = $courseStart - $prevEnd;
            if($diff > 0 && $start < $courseStart) {
                $users[] = User::find($id);
            } else if($end > $courseEnd && $start >= $courseEnd) {
                $users[] = User::find($id);
            }
            $prevEnd = $courseEnd;
        }

        return view('friendbreak.index', ['users' => $users]);
    }


}
