<?php

namespace App\Http\Controllers;

use App\Course;
use App\Friend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{

    public function allfriends(Request $request){
        $credentials = $request->only(['email', 'password']);
        if (Auth::once($credentials)) {
            $email = $request->get("email");
            $dataArray = User::where("email", $email)
                ->first()
                ->friends()
                ->join("users", "friends.receiver_id", "=", "users.id")
                ->get();
            $results = array();
            foreach ($dataArray as $data) {
                $item = array();
                $item['email'] = $data->email;
                $item['firstname'] = $data->firstname;
                $item['lastname'] = $data->lastname;
                $results[] = $item;
            }
            return response()->json($results, 200);
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }

    public function whereisfriend(Request $request){
        $credentials = $request->only(['email', 'password']);
        if (Auth::once($credentials)) {

            $validator = Validator::make($request->all(), [
                'friendemail' => [
                    'required', 'string',
                ],
                'day' => [
                    'required', 'integer',
                ],
                'time' => [
                    'required', 'integer',
                ]
            ]);

            if($validator->fails()) {
                return response()->json(['error' => 'bad or missing parameter'], 400);
            }

            $friendemail = $request->get("friendemail");
            $email = $request->get("email");
            $day = $request->get("day");
            $time = $request->get("time");

            $userFriend = User::where("email", $friendemail)->first();
            $user = User::where("email", $email)->first();

            if($this->isConfirmedFriend($user, $userFriend)) {
                $courses = $userFriend->courses()->get();
                $scheduleArray = array();
                foreach ($courses as $course) {
                    $schedules = $course->teachers()->where('course_teacher.day', '=', $day)->get();
                    foreach ($schedules as $schedule) {
                        $scheduleArray[] = $schedule->pivot;
                    }
                }

                $current_location = null;
                foreach ($scheduleArray as $schedule) {
                    $courseStart = $schedule->start;
                    $courseEnd = $schedule->end;
                    if ($time >= $courseStart && $time <= $courseEnd) {$current_location = $schedule;
                        break;
                    }
                }

                if(!isset($current_location)){
                    $results['course'] = "";
                    $results['section'] = "";
                } else {
                    $current_course = Course::find($current_location->course_id);
                    $results['course'] = $current_course->class;
                    $results['section'] = $current_course->section;
                }
                return response()->json($results, 200);
            }
            return response()->json(['error' => 'not a friend'], 400);
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }

    private function isConfirmedFriend($user, $userFriend){
        $friend_record1 = Friend::where("user_id", $user->id)
            ->where("receiver_id", $userFriend->id)
            ->where("confirmed", true)
            ->first();
        $friend_record2 = Friend::where("user_id", $userFriend->id)
            ->where("receiver_id", $user->id)
            ->where("confirmed", true)
            ->first();

        if($friend_record1 !== null && $friend_record2 !== null){
            return true;
        }
        return false;
    }

}
