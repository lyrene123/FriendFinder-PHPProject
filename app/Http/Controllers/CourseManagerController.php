<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\CourseUser;
use App\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CourseManagerController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request) {

        $registered_courses = User::find(Auth::user()->id)
            ->where("users.id", "=", Auth::user()->id)
            ->join("course_user", "course_user.user_id", "=", "users.id")
            ->join("courses", "courses.id", "=", "course_user.course_id")
            ->paginate(10);

        $available_courses = Course::select('class', 'section', 'title')
            ->paginate(10);

        return view('coursemanager.index', ['registered_courses' => $registered_courses,
            'available_courses' => $available_courses]);
    }

    public function add(Request $request, Course $course){
        return redirect('/coursemanager');
    }

    public function drop(Request $request, Course $course){
        $this->authorize('drop', $course);

        $registered_course = CourseUser::select('id')
            ->where('user_id', Auth::user()->id)
            ->where('course_id', $course->id);

        $registered_course->delete();

        return redirect('/coursemanager');
    }
}
