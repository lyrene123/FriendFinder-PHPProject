@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Course Manager</div>
                    <div class="panel-body">
                        @if(isset($registered_courses))
                            <h3 class="text-center">Hello, {{ $registered_courses[0]->firstname }}</h3>
                            @if(count($registered_courses) > 0)
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
                                                    <form action="{{ url('coursemanager/'.$course->course_id) }}" method="POST">
                                                        {{ csrf_field() }}
                                                        {{ method_field('DELETE') }}

                                                        <button type="submit" id="delete-registered-course-{{ $course->course_id }}" class="btn btn-danger">
                                                            <i class="fa fa-btn fa-trash"></i>Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach;
                                    </tbody>
                                </table>
                            @else
                                <h3 class="text-center">You have not registered for any courses</h3>
                            @endif
                        @endif
                    </div>
                    <div class="panel-heading"><h3 class="text-center">Register for a Course</h3></div>
                    <div class="panel-body">
                        <div class="input-group custom-search-form">
                            <form action="{{ url('coursemanager/search') }}" method="GET">
                                <input type="text" class="form-control" name="search_input" placeholder="Details...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default-sm" type="submit" id="search-courses">
                                        <i class="fa fa-search">Search for Course</i>
                                    </button>
                                </span>
                            </form>
                        </div>
                        <h4 class="text-center">Found Courses</h4>
                        <table class="table table-striped task-table">
                            <thead>
                                <th>Course ID</th>
                                <th>Class Name</th>
                                <th>Section</th>
                                <th>Title</th>
                                <th></th>
                            </thead>
                            <tbody>
                                @if(isset($courses) && count($courses) > 0)
                                    @foreach($courses as $c)
                                        <tr>
                                            <td>{{ $c->id }}</td>
                                            <td>{{ $c->class }}</td>
                                            <td>{{ $c->section }}</td>
                                            <td>{{ $c->title }}</td>
                                            <td></td>
                                            <td>
                                                <form action="{{ url('coursemanager/add/'.$c->id) }}" method="POST">
                                                    {{ csrf_field() }}
                                                    <button type="submit" id="add-registered-course-{{ $c->cid }}" class="btn btn-success">
                                                        <i class="fa fa-btn fa-trash">Add Course</i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection