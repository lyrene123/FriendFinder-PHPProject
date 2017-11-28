
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            @if(Auth::check())

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Search for new friends
                    </div>

                    <div class="panel-body">
                    @include('common.errors')

                    <!-- Search for friend Form -->
                        <form action="/friend" method="POST" class="form-horizontal">
                        {{ csrf_field() }}

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
                    </div>
                </div>



                @if (count($friends) > 0)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Friends list
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped task-table">
                                <thead>
                                <th>Here are your friends</th>
                                <th>&nbsp;</th>
                                </thead>
                                <tbody>
                                @foreach ($friends as $friend)
                                    <tr>
                                        <!--First name and last name of each friends-->
                                        <td class="table-text">
                                            <strong>{{ $friend->firstname }}</strong>
                                            <strong>{{ $friend->lastname }}</strong>
                                        </td>

                                        @if($friend->confirmed == true)
                                            <td>
                                                <strong>CONFIRMED</strong>
                                            </td>
                                        @else
                                            <td>
                                                <strong>PENDING</strong>
                                            </td>
                                        @endif

                                        @if(Auth::check() && $friend->userCanEdit(Auth::user()))
                                            <td>
                                                <form action="{{url('friend/' . $friend->id)}}" method="POST">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}

                                                    <button type="submit" id="delete-friend-{{ $friend->user_id }}" class="btn btn-danger">
                                                        <i class="fa fa-btn fa-trash"></i>Unfriend
                                                    </button>
                                                </form>
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $friends->render() !!}
                        </div>
                    </div>
                @else
                    <div class="panel-heading">
                        You have no friends :(
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection