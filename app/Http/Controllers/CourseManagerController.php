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
    /**
     * CourseManagerController constructor.
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {

        $registered_courses = User::find(Auth::user()->id)
            ->where("users.id", "=", Auth::user()->id)
            ->join("course_user", "course_user.user_id", "=", "users.id")
            ->join("courses", "courses.id", "=", "course_user.course_id")
            ->paginate(20);

        return view('coursemanager.index', ['registered_courses' => $registered_courses,]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function search(Request $request){

        $registered_courses = User::find(Auth::user()->id)
            ->where("users.id", "=", Auth::user()->id)
            ->join("course_user", "course_user.user_id", "=", "users.id")
            ->join("courses", "courses.id", "=", "course_user.course_id")
            ->paginate(20);

        $this->validate($request, ['search_input' => 'required',]);

        $input = $request->input("search_input");

        $search_class = Course::select('class', 'section', 'title')
            ->where('class', 'like', '%'.$input.'%')
            ->paginate(20);
        $search_title = Course::select('class', 'section', 'title')
            ->where('title', 'like', '%'.$input.'%')
            ->paginate(20);

        //$search_teacher = Course:: select('class', 'section', 'title')

        return view('coursemanager.index', ['registered_courses' => $registered_courses,
            'search_class' => $search_class, 'search_title' => $search_title,]);
    }

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function drop(Request $request, Course $course){
        $this->authorize('drop', $course);

        $registered_course = CourseUser::select('id')
            ->where('user_id', Auth::user()->id)
            ->where('course_id', $course->id);

        $registered_course->delete();

        return redirect('/coursemanager');
    }

    public function add(Request $request, Course $course){

        return redirect('/coursemanager');
    }
}
