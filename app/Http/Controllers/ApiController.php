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

    /**
     * ApiController constructor
     *
     * Initialize the default value of $days and $hours.
     * $days have only 5 days from 1 to 5
     * $hours start from 10am to 5pm
     */
    public function __construct() {
        $this->hours = ['1000', '1030', '1100', '1130'
            , '1200', '1230', '1300', '1330', '1400', '1430', '1500', '1530', '1600'
            , '1630', '1700'];
        for($i=1; $i<=5; $i++) {
            $this->days[] = $i;
        }
    }

    /**
     * Get a json response of all the friends who are in the same course and section.
     * Validate the request if the email, password, coursename and section! if either
     * email or password is wrong, return a json response of error: invalid_credentail.
     * If either coursename or section is wrong, return a json response of error:
     * bad or missing parameter. If everything is validated, return a json response
     * of users with only email, firstname and lastname.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        $coursename = $request->input('coursename');
        $section = $request->input('section');

        $user = User::where('email', $request->input('email'))->first();
        $friends = $user->friends()->where('confirmed', true)->get();
        $friendsInSameCourse = $this->findFriendsInSameCourse($friends, $coursename, $section);

        return response()->json($this->createJsonUser($friendsInSameCourse), 200);
    }

    /**
     * Get a Json response of the friends who are on break. The request must have
     * valid email, password, day, start and end. If the email or password is not
     * right, return a json response of error: invalid_credentials. If the day,
     * start and end is not valid, return a json response of error: bad or missing
     * parameter. If everything is valid, return a json response of all the friends
     * with only email, firstname and lastname.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function findFriendBreak(Request $request) {
        //check credentials
        $credentials = $request->only('email', 'password');
        $valid = Auth::once($credentials); //logs in for single request

        if (!$valid) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

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

        $day = $request->input('day');
        $start = $request->input('start');
        $end = $request->input('end');

        $user = User::where('email', $request->input('email'))->first();
        $friends = $user->friends()->where('confirmed', true)->get();
        $breakFriends = $this->findFriendsOnBreak($friends, $day, $start, $end);

        return response()->json($this->createJsonUser($breakFriends), 200);
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
        $prevEnd = 2400;
        $firstCourse = isset($friendScheduleOfADay[0]) ? $friendScheduleOfADay[0] : 0;
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
     * Find friends who are in the same course and section. The coursename and section
     * is not case-sensitive.
     *
     * @param $friends
     * @param $coursename
     * @param $section
     * @return array
     */
    private function findFriendsInSameCourse($friends, $coursename, $section) {
        $coursename = trim(strtolower($coursename));
        $section = trim(strtolower($section));

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
        return $users;
    }

    /**
     * Create the array of User with only email, firstname and lastname
     * as the key from the array of User objects.
     *
     * @param $users - array of User object
     * @return array of User with only email, firstname and lastname
     */
    private function createJsonUser($users) {
        $jsonUser = array();
        foreach($users as $user) {
            $item = array();
            $item['email'] = $user->email;
            $item['firstname'] = $user->firstname;
            $item['lastname'] = $user->lastname;
            $jsonUser[] = $item;
            unset($item);
        }
        return $jsonUser;
    }
  
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
