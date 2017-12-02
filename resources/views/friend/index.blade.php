
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-1 col-sm-10">
            @if(Auth::check())
                <div class="row padding-s">
                    <div class="col-lg-12 text-center">
                        <h2>Manage your friends</h2>
                    </div>
                </div>
                @include('common.messages')
                <div class="row">
                    <div id="content" class="">
                        @if (isset($friends) && count($friends) > 0)
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div  class="padding-s">
                                        <nav>
                                            <ul class="nav nav-tabs">
                                                <li class="nav-item active"><a href=" {{ route('friends') }} ">Friends</a></li>
                                                <li class="nav-item"><a href=" {{ route('search') }} ">Search Friends</a></li>
                                                <li class="nav-item"><a href="{{ route('requests') }}">Friend Request</a></li>
                                            </ul>
                                        </nav>
                                    </div>
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

                                                @if($friend->confirmed === true)
                                                    <td>
                                                        <strong>CONFIRMED</strong>
                                                    </td>
                                                @else
                                                    <td>
                                                        <strong>PENDING</strong>
                                                    </td>
                                                @endif


                                                    <td>
                                                        <form action="{{url('/friend/' . $friend->id)}}" method="POST">
                                                            {{ method_field('DELETE') }}
                                                            {{ csrf_field() }}

                                                            <button type="submit" id="delete-friend-{{ $friend->id }}" class="btn btn-danger">
                                                                <i class="fa fa-btn fa-trash"></i>Unfriend
                                                            </button>
                                                        </form>
                                                    </td>
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
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection