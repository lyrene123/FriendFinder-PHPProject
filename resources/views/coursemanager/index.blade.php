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
                    <div class="panel-heading">Register for a course</div>
                    <div class="panel-body">
                        @if(isset($available_courses) && count($available_courses) > 0)
                            <table class="table table-striped task-table">
                                <thead>
                                    <th>Class ID</th>
                                    <th>Section</th>
                                    <th>Title</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach($available_courses as $course)
                                        <tr>
                                            <td>{{ $course->class }}</td>
                                            <td>{{ $course->section }}</td>
                                            <td>{{ $course->title }}</td>
                                            <td>
                                                <form action="{{ url('coursemanager/add/'.$course->course_id) }}" method="POST">
                                                    {{ csrf_field() }}

                                                    <button type="submit" id="add-registered-course-{{ $course->course_id }}" class="btn btn-danger">
                                                        <i class="fa fa-btn fa-trash"></i>Add Course
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection