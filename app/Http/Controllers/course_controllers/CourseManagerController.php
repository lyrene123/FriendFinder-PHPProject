<?php

namespace App\Http\Controllers;

use App\User;
use App\Course;
use App\CourseUser;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

/**
 * Controller the manager the course manager page.
 */
class CourseManagerController extends Controller
{
    /**
     * Creates an instance of the controller.
     *
     * CourseManagerController constructor.
     */
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Method that associates the index view with it's associated models.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {

        // Get all courses the user is currently registered in
        $registered_courses = User::find(Auth::user()->id)
            ->where("users.id", "=", Auth::user()->id)
            ->join("course_user", "course_user.user_id", "=", "users.id")
            ->join("courses", "courses.id", "=", "course_user.course_id")
            ->get();

        $user = User::where("users.id", "=", Auth::user()->id)->get();

        return view('coursemanager.index', ['registered_courses' => $registered_courses, 'user' => $user,]);
    }

    /**
     * Associates the search view with it's associated models.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function search(Request $request){

        // Get all courses the user is currently registered in
        $registered_courses = User::find(Auth::user()->id)
            ->where("users.id", "=", Auth::user()->id)
            ->join("course_user", "course_user.user_id", "=", "users.id")
            ->join("courses", "courses.id", "=", "course_user.course_id")
            ->get();

        $user = User::where("users.id", "=", Auth::user()->id)->get();

        // validate the user input
        $this->validate($request, ['search_input' => 'required',]);
        $input = $request->input("search_input");

        $courses = array();

        // find the courses associated with the user input
        $courses_by_name = Course::where('class', 'ilike', '%'.$input.'%')->get();
        $courses_by_title = Course::where('title', 'ilike', '%'.$input.'%')->get();

        // courses information by course name
        foreach($courses_by_name as $cbn){
            if(!in_array($cbn, $courses)){
                if($registered_courses->where('title', $cbn->title)->count() === 0){
                    $item = array();
                    $item['id'] = $cbn->id;
                    $item['class'] = $cbn->class;
                    $item['section'] = $cbn->section;
                    $item['title'] = $cbn->title;
                    $item['teacher'] = "";
                    $courses[] = $item;
                }
            }
        }

        // courses information by course title
        foreach($courses_by_title as $cbt){
            if(!in_array($cbt, $courses)){
                if($registered_courses->where('title', $cbt->title)->count() === 0){
                    $item = array();
                    $item['id'] = $cbt->id;
                    $item['class'] = $cbt->class;
                    $item['section'] = $cbt->section;
                    $item['title'] = $cbt->title;
                    $item['teacher'] = "";
                    $courses[] = $item;
                }
            }
        }

        // courses information by course teacher's name
       $allCourses = Course::all();
       foreach ($allCourses as $aCourse){
           $found = $aCourse->teachers()
                        ->where('name','ilike', "%$input%")
                        ->get();
           if(count($found) > 0 && !in_array($aCourse, $courses)){
               if($registered_courses->where('title', $aCourse->title)->count() === 0) {
                   $item = array();
                   $item['id'] = $aCourse->id;
                   $item['class'] = $aCourse->class;
                   $item['section'] = $aCourse->section;
                   $item['title'] = $aCourse->title;
                   $item['teacher'] = $found[0]->name;
                   $courses[] = $item;
               }
           }
       }

       $paginated_courses = $this->constructPagination($courses);

        return view('coursemanager.index', ['registered_courses' => $registered_courses, 'courses' => $paginated_courses,
            'user' => $user,]);
    }

    /**
     *  Associate the delete view with it's model
     *
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
     *  Associate the add view with it's model
     *
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

    /**
     * Creates Pagination for a given data array and with a number of records
     * per page to display
     *
     * Solution based on
     * http://blog.hazaveh.net/2016/03/laravel-5-manual-pagination-from-array/
     *
     * @param $dataArr the array of data
     * @param $perPage the number of data to show per page
     * @return LengthAwarePaginator Pagination object
     */
    private function constructPagination($dataArr){
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $col = new Collection($dataArr);
        $perPage = 10;
        $entries = new LengthAwarePaginator($col->forPage($currentPage, $perPage), $col->count(), $perPage, $currentPage);
        $entries->setPath(LengthAwarePaginator::resolveCurrentPath());
        return $entries;
    }
}
