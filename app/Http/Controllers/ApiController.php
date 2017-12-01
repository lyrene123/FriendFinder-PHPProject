<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;

class ApiController extends Controller
{

    private $hours;
    private $days;

    public function __construct() {
        $this->hours = ['1000', '1030', '1100', '1130'
            , '1200', '1230', '1300', '1330', '1400', '1430', '1500', '1530', '1600'
            , '1630', '1700'];
        for($i=1; $i<=5; $i++) {
            $this->days[] = $i;
        }
    }

    public function findCourseFriends(Request $request) {
        $credentials = $request->only('email', 'password');
        $valid = Auth::once($credentials); //logs in for single request

        if (!$valid) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        $validator = Validator::make($request->all(), [
            'coursename' => [
                'required', 'string'
            ],
            'section' => [
                'required', 'string'
            ],
        ]);

        if($validator->fails()) {
            return response()->json(['error' => 'bad or missing parameter'], 400);
        }

        $coursename = trim(strtolower($request->input('coursename')));
        $section = trim(strtolower($request->input('section')));

        $user = User::where('email', $request->input('email'))->first();
        $friends = $user->friends()->where('confirmed', true)->get();
        $users = array();
        foreach($friends as $friend) {
            $userFriend = User::find($friend->receiver_id);
            $courses = $userFriend->courses()->get();
            foreach ($courses as $course) {
                $dbSection = trim(strtolower($course->section));
                $class = trim(strtolower($course->class));
                if ($section === $dbSection && $coursename === $class) {
                    $users[$userFriend->id] = $userFriend;
                }
            }
        }

        $jsonUser = array();
        foreach($users as $user) {
            $item = array();
            $item['email'] = $user->email;
            $item['firstname'] = $user->firstname;
            $item['lastname'] = $user->lastname;
            $jsonUser[] = $item;
            unset($item);
        }

        return response()->json($jsonUser, 200);
    }

    public function findFriendBreak(Request $request) {
        //check credentials
        $credentials = $request->only('email', 'password');
        $valid = Auth::once($credentials); //logs in for single request

        if (!$valid) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        $day = $request->input('day');
        $start = $request->input('start');
        $end = $request->input('end');

        $validator = Validator::make($request->all(), [
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
        ]);

        if($validator->fails()) {
            return response()->json(['error' => 'bad or missing parameter'], 400);
        }

        $user = User::where('email', $request->input('email'))->first();
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
            usort($scheduleArray, function($a, $b) {
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

        $jsonUser = array();
        foreach($users as $user) {
            $item = array();
            $item['email'] = $user->email;
            $item['firstname'] = $user->firstname;
            $item['lastname'] = $user->lastname;
            $jsonUser[] = $item;
            unset($item);
        }
        return response()->json($jsonUser, 200);
    }
}
