<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseManagerController extends Controller
{
    public function __construct(){

    }

    public function index() {
        return view('coursemanager.index');
    }
}
