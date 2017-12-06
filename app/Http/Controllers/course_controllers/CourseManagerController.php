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
 * Controller class that handles the course manager page.
 * Handles the logged in user's action of adding a course and dropping a course.
 * Handles as well the feature of searching for courses matching the search input of the user based
 * on the title, course name, and teacher.
 *
 * @author Lyrene Labor
 * @author Pengkim Sy
 * @author Peter Bellefleur
 * @author Phil Langlois
 * @package App\Http\Controllers
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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        // Get all courses the user is currently registered in
        $user = User::find(Auth::user()->id);
        $registered_courses = $user->courses()->get();

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
        $user = User::find(Auth::user()->id);
        $registered_courses = $user->courses()->get();

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
    public function drop(Course $course){
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
    public function add(Course $course){

        $enrollment_course = CourseUser::firstOrCreate([
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
