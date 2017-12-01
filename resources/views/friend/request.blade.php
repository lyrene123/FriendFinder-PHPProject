@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="col-sm-offset-1 col-sm-10">
            @if(Auth::check())
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Friend Requests</h2>
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
                                Here are your pending friend requests
                            </div>

                            <div class="panel-body">
                                @if (isset($requests) && count($requests) > 0)
                                    <table class="table table-striped task-table">
                                        <tbody>
                                        @foreach($requests as $request)
                                            <tr>
                                                <!--First name and last name-->
                                                <td class="table-text">
                                                    <strong>{{ $request->firstname }}</strong>
                                                    <strong>{{ $request->lastname }}</strong>
                                                </td>
                                                <td class="fillWidth"></td>
                                                <td>
                                                    <form class="d-inline" action="{{ '/requests/accept/' . $request->id }}" method="POST">
                                                        {{ csrf_field() }}

                                                        <button type="submit" id="accept-friend-{{ $request->id }}" class="btn btn-success">
                                                            <i class="fa fa-btn fa-plus"></i>Accept
                                                        </button>
                                                    </form>

                                                    <form class="d-inline" action="{{ '/requests/decline/' . $request->id }}" method="POST">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}

                                                        <button type="submit" id="decline-friend-{{ $request->id }}" class="btn btn-danger">
                                                            <i class="fa fa-btn fa-trash"></i>Decline
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    {!! $requests->render() !!}
                                @endif
                                @if (isset($requests) && count($requests) === 0)
                                    <div class="panel-heading">
                                        No pending requests
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