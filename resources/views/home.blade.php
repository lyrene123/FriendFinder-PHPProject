@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="text-center">Hello, {{ $user[0]->firstname }}!</h3>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                @if(isset($reistered_courses) && count($registered_courses) > 0)
                   <table class="table table-striped task-table">
                         <thead>
                            <th>Class ID</th>
                            <th>Section</th>
                            <th>Title</th>
                         </thead>
                         <tbody>
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
                    <p class="text-center">You have not registered for any courses.</p>
                @endif
                    @if (Auth::check())
                        <section class="text-center">
                            <a href=" {{ route('coursemanager') }}">Manage your courses</a>
                        </section>
                    @endif
                </div>
                <div>
                    @if (Auth::check())
                        <section class="text-center">
                            <a href=" {{ route('friends') }} ">Manage your friends</a>
                        </section>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
