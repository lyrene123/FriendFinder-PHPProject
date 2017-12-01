@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="col-sm-offset-1 col-sm-10">
            @if(Auth::check())
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Search Friends</h2>
                    </div>
                </div>

                <div id="main" class="row">
                    <div id="sidebar" class="col-md-3">
                        <nav id="sidebar-nav">
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href=" {{ route('friends') }} ">Back to Manage Friends</a></li>
                            </ul>
                        </nav>
                    </div>

                    <div id="content" class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Search for new friends
                            </div>

                            <div class="panel-body">
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
                                            <button type="submit" class="btn btn-default">
                                                <i class="fa fa-btn fa-plus"></i>Search
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                @if (isset($users) && count($users) > 0)
                                    <table class="table table-striped task-table">
                                        <thead>
                                        <th>Search results</th>
                                        <th>&nbsp;</th>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $user)
                                            {{-- var_dump($users[$i]) --}}
                                            {{--Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage()--}}
                                           <tr>
                                                <!--First name and last name-->
                                                   <td class="table-text">
                                                        <strong>{{ $user['firstname'] }}</strong>
                                                        <strong>{{ $user['lastname'] }}</strong>
                                                    </td>
                                                    <!-- program of each user -->
                                                    <td>
                                                        <strong>{{ $user['program'] }}</strong>
                                                    </td>
                                                    <!-- add friend button or unfriend button only if user is not you -->
                                                  @if ($user['isFriends'] && $user['id'] !== \Illuminate\Support\Facades\Auth::user()->id)
                                                        <td>
                                                            <form action="{{url('/friend/' . $user['id'])}}" method="POST">
                                                                {{ method_field('DELETE') }}
                                                                {{ csrf_field() }}

                                                                <button type="submit" id="delete-friend-{{ $user['id'] }}" class="btn btn-danger">
                                                                    <i class="fa fa-btn fa-trash"></i>Unfriend
                                                                </button>
                                                            </form>
                                                        </td>
                                                    @endif
                                                    @if (!$user['isFriends'] && $user['id'] !== \Illuminate\Support\Facades\Auth::user()->id)
                                                        <td>
                                                            <form action="{{url('search/add/' . $user['id'])}}" method="POST">
                                                                {{ csrf_field() }}

                                                                <button type="submit" id="add-user-{{ $user['id'] }}" class="btn btn-success">
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