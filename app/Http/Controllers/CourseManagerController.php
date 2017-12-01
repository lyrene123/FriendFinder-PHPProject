<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\CourseUser;
use App\Teacher;
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
            ->get();

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
            ->get();

        $this->validate($request, ['search_input' => 'required',]);
        $input = $request->input("search_input");

        $courses = array();

        $courses_by_name = Course::where('class', 'like', '%'.$input.'%')->get();
        $courses_by_title = Course::where('title', 'like', '%'.$input.'%')->get();

        foreach($courses_by_name as $cbn){
            if(!in_array($cbn, $registered_courses)){
                if($registered_courses->where('title', $cbn->title)->count() === 0){
                    $courses[] = $cbn;
                }
            }
        }

        foreach($courses_by_title as $cbt){
            if(!in_array($cbt, $courses)){
                if($registered_courses->where('title', $cbt->title)->count() === 0){
                    $courses[] = $cbt;
                }
            }
        }

        return view('coursemanager.index', ['registered_courses' => $registered_courses, 'courses' => $courses,]);
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

    /**
     * @param Request $request
     * @param Course $course
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(Request $request, Course $course){

        $enrollment_course = Courseuser::firstOrCreate([
            'user_id' => Auth::user()->id,
            'course_id' => $course->id,
        ]);

        return redirect('/coursemanager');
    }
}
