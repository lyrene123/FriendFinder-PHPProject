@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="col-sm-offset-1 col-sm-10">
            <!-- Only if the user is authenticated, display the rest of the page -->
            @if(Auth::check())
                <div class="row padding-s">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Search Friends</h2>
                    </div>
                </div>

                <div id="main" class="row">
                    <div id="content" class="col-md-12">
                        <div class="panel panel-default">

                            <div class="panel-body">
                                <div  class="padding-s">
                                    <nav>
                                        <ul class="nav nav-tabs">
                                            <li class="nav-item"><a href=" {{ route('friends') }} ">Friends</a></li>
                                            <li class="nav-item active"><a href=" {{ route('search') }} ">Search Friends</a></li>
                                            <li class="nav-item"><a href="{{ route('requests') }}">Friend Request</a></li>
                                        </ul>
                                    </nav>
                                </div>
                            @include('common.errors')
                            <!-- Search for friend Form -->
                                <form action="/search/results" method="GET" class="form-horizontal">
                                    <div class="form-group">
                                        <label for="search-fname" class="col-sm-3 control-label">First Name</label>

                                        <div class="col-sm-6">
                                            <input type="text" name="fname" id="search-fname" class="form-control" value="{{ old('fname') }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="search-lname" class="col-sm-3 control-label">Last Name</label>

                                        <div class="col-sm-6">
                                            <input type="text" name="lname" id="search-lname" class="form-control" value="{{ old('lname') }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-6">
                                            <button type="submit" class="btn btn-success">
                                                <i class=""></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- Only if there are search results for users, display the list of users after the search -->
                                @if (isset($users) && count($users) > 0)
                                    <table class="table table-striped task-table">
                                        <thead>
                                            <th class="text-center"><h4>Search results</h4></th>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $user)
                                           <tr>
                                                <!--First name and last name-->
                                                   <td class="text-center">
                                                        <strong>{{ $user['firstname'] }}</strong>
                                                        <strong>{{ $user['lastname'] }}</strong>
                                                    </td>
                                                    <!-- program of each user -->
                                                    <td class="text-center">
                                                        <strong>{{ $user['program'] }}</strong>
                                                    </td>
                                                    <!-- dont add friend button or unfriend button user is you -->
                                                  @if ($user['isFriends'] && $user['id'] !== \Illuminate\Support\Facades\Auth::user()->id)
                                                        <td class="text-center">
                                                            <form action="{{url('/friend/' . $user['id'])}}" method="POST">
                                                                {{ method_field('DELETE') }}
                                                                {{ csrf_field() }}

                                                                <button type="submit" id="delete-friend-{{ $user['id'] }}" class="btn btn-danger">
                                                                    <i class="fa fa-btn fa-trash"></i>Unfriend
                                                                </button>
                                                            </form>
                                                        </td>
                                                    @endif
                                                    <!-- add friend button or unfriend button if user is not you -->
                                                    @if (!$user['isFriends'] && $user['id'] !== \Illuminate\Support\Facades\Auth::user()->id)
                                                        <td class="text-center">
                                                            <form action="{{url('search/add/' . $user['id'])}}" method="POST">
                                                                {{ csrf_field() }}

                                                                <button type="submit" id="add-user-{{ $user['id'] }}" class="btn btn-blue">
                                                                    <i class="fa fa-btn fa-plus"></i>Add Friend
                                                                </button>
                                                            </form>
                                                        </td>
                                                    @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {!! $users->appends($_GET)->render() !!}
                                @endif
                            <!-- Display a message to the user if no search result found -->
                                @if (isset($users) && count($users) === 0)
                                    <div class="panel-heading">
                                        No results found
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection