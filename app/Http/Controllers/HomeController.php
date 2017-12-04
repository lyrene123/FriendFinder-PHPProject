<?php

namespace App\Http\Controllers;

use App\Friend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $registered_courses = User::find(Auth::user()->id)
            ->where("users.id", "=", Auth::user()->id)
            ->join("course_user", "course_user.user_id", "=", "users.id")
            ->join("courses", "courses.id", "=", "course_user.course_id")
            ->get();

        $user = User::where("users.id", "=", Auth::user()->id)->get();

        return view('home', ['registered_courses' => $registered_courses, 'user' => $user,]);
    }
}
