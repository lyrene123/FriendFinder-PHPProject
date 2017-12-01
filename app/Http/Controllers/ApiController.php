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

    /**
     * Get a json response of all the friends who are in the same course and section.
     * Validate the request if the email, password, coursename and section! if either
     * email or password is wrong, return a json response of error: invalid_credentail.
     * If either coursename or section is wrong, return a json response of error:
     * bad or missing parameter. If everything is validated, return a json response
     * of users with only email, firstname and lastname.
     *
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
        $breakFriends = $this->findFrindsOnBreak($friends, $day, $start, $end);

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
    private function findFrindsOnBreak(array $friends, $day, $start, $end) {
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
    private function checkIfUserIsOnBreak(array $friendScheduleOfADay, $start, $end) {
        $prevEnd = 0;
        foreach($friendScheduleOfADay as $schedule) {
            $courseStart = $schedule->start;
            $courseEnd = $schedule->end;
            $diff = $courseStart - $prevEnd;
            if($diff > 0 && $start < $courseStart) {
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

}
