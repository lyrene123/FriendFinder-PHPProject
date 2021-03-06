@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <!-- retrieve user name to display !-->
                    <h3 class="text-center">Hello, {{ $user->firstname }}!</h3>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- check if courses variable not empty; display course table if so !-->
                    @if(isset($registered_courses) && count($registered_courses) > 0)
                        <table class="table table-striped task-table">
                            <thead>
                                <th>Class ID</th>
                                <th>Section</th>
                                <th>Title</th>
                            </thead>
                            <tbody>
                            <!-- loop through courses variable, display each individual value !-->
                            @foreach($registered_courses as $course)
                                <tr>
                                    <td>{{ $course->class }}</td>
                                    <td>{{ $course->section }}</td>
                                    <td>{{ $course->title }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                            <!-- if empty, no courses registered - display to user !-->
                            <p class="text-center">You have not registered for any courses.</p>
                    @endif
                    @if (Auth::check())
                        <section class="text-center">
                            <a href=" {{ route('coursemanager') }}">Manage your courses</a>
                            <br/>
                        </section>
                    @endif

                    <!-- check if friends variable not empty; display friends table if so !-->
                    @if(isset($friends) && count($friends) > 0)
                        <table class="table table-striped task-table">
                            <thead>
                                <th>Friends</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <!-- loop through friends variable, display each individual value !-->
                                @foreach ($friends as $friend)
                                    <!-- only display friends whose 'confirmed' status is true !-->
                                    @if($friend['confirmed'] === true)
                                        <tr>
                                            <td class="table-text">
                                                <strong>{{ $friend['firstname'] }}</strong>
                                                <strong>{{ $friend['lastname'] }}</strong>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        {!! $friends->render() !!}
                    <!-- if empty, no friends added - display to user !-->
                    @else
                        <div class="panel-heading">
                            You have no registered friends.
                        </div>
                    @endif
                    @if (Auth::check())
                        <section class="text-center">
                            <!-- if pending variable not empty, user has incoming requests - display to user !-->
                            @if(isset($pending) && count($pending) > 0)
                                <a href=" {{ route('requests') }}"> You have pending friend requests.</a>
                                <br/>
                            @endif
                            <a href=" {{ route('friends') }} ">Manage your friends</a>
                            <br/>
                            <a href=" {{ route('friendbreak') }}">Find friends on break</a>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
