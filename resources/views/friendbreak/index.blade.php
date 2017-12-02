@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                @if (Auth::check())
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="text-center padding-s">Want to know your friends who is on break?</h2>
                        <form action="{{ url('friendbreak') }}/search" method="GET">
                            <div class="form-group">
                                <label for="day" class="col-xs-1 col-sm-1 text-right text-preserve padding-x-none">Day: </label>
                                <div class="col-xs-4 col-sm-2 padding-x-none">
                                    <select name="day" id="day" class="form-control">
                                        <option selected></option>
                                        <option value="1">Monday</option>
                                        <option value="2">Tuesday</option>
                                        <option value="3">Wednesday</option>
                                        <option value="4">Thursday</option>
                                        <option value="5">Friday</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="start" class="col-xs-3 col-sm-2 text-right text-preserve padding-x-none">Start Time: </label>
                                <div class="col-xs-4 col-sm-2 padding-x-none">
                                    <select name="start" id="start" class="form-control">
                                        <option selected></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="end" class="col-xs-3 col-sm-2 text-right text-preserve padding-x-none">End Time: </label>
                                <div class="col-xs-4 col-sm-2 padding-x-none">
                                    <select name="end" id="end" class="form-control">
                                        <option selected></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <input type="submit" class="btn btn-blue" value="Search" name="search">
                            </div>
                        </form>
                        @include('common.errors')
                    </div>

                    <div class="panel-body padding-m">
                        @if(isset($users) && count($users) > 0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->firstname }}</td>
                                        <td>{{ $user->lastname }}</td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $users->appends($_GET)->render() !!}
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
