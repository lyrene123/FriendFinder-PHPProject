@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <!-- Only if an existing user passed to the view exists, display the rest of the page -->
                        @if(isset($user))
                            <h3 class="text-center">Hello, {{ $user->firstname }} here are your currently registered courses</h3>
                            <!-- Only if user has registered courses, display list of courses -->
                            @if(isset($registered_courses) && count($registered_courses) > 0)
                                <table class="table table-striped task-table">
                                    <thead>
                                        <th>Class ID</th>
                                        <th>Section</th>
                                        <th>Title</th>
                                    <th></th>
                                    </thead>
                                    <tbody>
                                    @foreach($registered_courses as $course)
                                        <tr>
                                            <td>{{ $course->class }}</td>
                                            <td>{{ $course->section }}</td>
                                            <td>{{ $course->title }}</td>
                                            <td>
                                                <form action="{{ url('coursemanager/'. $course->id) }}" method="POST">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}

                                                    <button type="submit" id="delete-registered-course-{{ $course->id }}" class="btn btn-danger">
                                                        <i class="fa fa-btn fa-trash"></i>Drop
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h3 class="text-center">You have not registered for any courses</h3>
                            @endif
                        @endif
                    </div>
                    <div class="panel-heading">
                        <h3 class="text-center">Register for a Course</h3>
                    </div>
                    <div class="panel-body">
                        <div class="">
                            <form action="{{ url('coursemanager/search') }}" method="GET">
                                <div class="col-sm-5 col-sm-offset-2">
                                    <input type="text" class="form-control" name="search_input" placeholder="Details...">
                                </div>
                                <button class="btn btn-success" type="submit" id="search-courses">
                                    <i class="fa fa-search"></i>Search for Course
                                </button>
                            </form>
                        </div>
                        <!-- Only if there are search results of courses, display the list of courses after the search -->
                        @if((isset($courses) && count($courses) > 0))
                            <h4 class="text-center">Found Courses</h4>
                            <table class="table table-striped task-table">
                                <thead>
                                <th>Class Name</th>
                                <th>Section</th>
                                <th>Title</th>
                                <th>Teacher</th>
                                <th></th>
                                </thead>
                                <tbody>
                                @foreach($courses as $c)
                                    <tr>
                                        <td>{{ $c['class'] }}</td>
                                        <td>{{ $c['section'] }}</td>
                                        <td>{{ $c['title'] }}</td>
                                        <td>{{ $c['teacher'] }}</td>
                                        <td>
                                            <form action="{{ url('coursemanager/add/'. $c['id']) }}" method="POST">
                                                {{ csrf_field() }}
                                                <button type="submit" id="add-registered-course-{{ $c['id'] }}" class="btn btn-success">
                                                    <i class="fa fa-btn fa-trash">Add Course</i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                {!! $courses->appends($_GET)->render() !!}
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection