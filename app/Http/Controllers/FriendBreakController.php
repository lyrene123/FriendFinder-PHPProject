<?php

namespace App\Http\Controllers;

use App\CourseTeacher;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class FriendBreakController extends Controller
{
    public function __construct() {
//        $this->middleware('auth');

    }

    public function index() {
        return view('friendbreak.index');
    }

    public function search(Request $request) {
        //$this->validate(); Need to do this validation

        $day = 1;
        $start = 900;
        $end = 1300;
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
//                    var_dump($userFriend->id);
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
