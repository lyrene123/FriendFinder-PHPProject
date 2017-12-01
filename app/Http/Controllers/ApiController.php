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
    /**
     * Returns as a json response all the confirmed friends
     * of a user's email. Validates and authenticates first
     * the credentials of the user sending the request. The json reponse will
     * be empty if user has no friends. Reponse will contain an error if authentication
     * failed.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allfriends(Request $request){
        //retrieve credentials
        $credentials = $request->only(['email', 'password']);
        //login only once and perform query only if login is successful
        if (Auth::once($credentials)) {
            $email = $request->get("email");
            //retrieve all confirmed friends of the user
            $dataArray = User::where("email", $email)
                ->first()
                ->friends()
                ->where("confirmed", true)
                ->join("users", "friends.receiver_id", "=", "users.id")
                ->get();
            $results = $this->buildFriendsArray($dataArray);
            return response()->json($results, 200);
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }

    /**
     * Builds and returns an array with only specific information
     * from the friends list of the user
     *
     * @param $dataArray Result array of the query
     * @return array containing only the info we want to send through Json response
     */
    private function buildFriendsArray($dataArray){
        $results = array();
        foreach ($dataArray as $data) {
            $item = array();
            $item['email'] = $data->email;
            $item['firstname'] = $data->firstname;
            $item['lastname'] = $data->lastname;
            $results[] = $item;
        }
        return $results;
    }

    /**
     * Returns as a json response the location of a specific friend at a specific time
     * Validates and authenticates first the credentials of the user sending the request.
     * The json response will contain a course if a friend is currently in class or an empty
     * response if friend is not in class. Response will contain an error if authentication
     * failed, if invalid input, and if provided friend information is not your friend.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function whereisfriend(Request $request){
        $credentials = $request->only(['email', 'password']); //retrieve credentials
        //login only once
        if (Auth::once($credentials)) {

            //validate the input
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
            //return an error message if invalid input
            if($validator->fails()) {
                return response()->json(['error' => 'bad or missing parameter'], 400);
            }

            //retrieve all necessary input from the request object
            $friendemail = $request->get("friendemail");
            $email = $request->get("email");
            $day = $request->get("day");
            $time = $request->get("time");
            $userFriend = User::where("email", $friendemail)->first();
            $user = User::where("email", $email)->first();

            //verify if the friend info consist of your friend, if not return an error response
            if($this->isConfirmedFriend($user, $userFriend)) {
                $courses = $userFriend->courses()->get(); //get all courses of the friend
                //loop through each course and retrieve its schedule from the course_teacher pivot table
                $scheduleArray = array();
                foreach ($courses as $course) {
                    $schedules = $course->teachers()->where('course_teacher.day', '=', $day)->get();
                    foreach ($schedules as $schedule) {
                        $scheduleArray[] = $schedule->pivot;
                    }
                }
                //check if the input time is in between any start/end interval of the friend's courses
                $current_location = null;
                foreach ($scheduleArray as $schedule) {
                    $courseStart = $schedule->start;
                    $courseEnd = $schedule->end;
                    if ($time >= $courseStart && $time <= $courseEnd) {$current_location = $schedule;
                        break;
                    }
                }
                //build the response message with the appropriate info
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

    /**
     * Verifies whether or not two users are friends by checking if records
     * exist in the Friend table for those two users.
     *
     * @param $user A User
     * @param $userFriend Another User to compare if friends with $user
     * @return bool boolean whether or not both users are friends
     */
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
