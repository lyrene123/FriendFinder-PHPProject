
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-1 col-sm-10">
            @if(Auth::check())
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading">Manage your friends</h2>
                    </div>
                </div>
                @include('common.messages')
                <div id="main" class="row">
                    <div id="sidebar" class="col-md-3">
                        <nav id="sidebar-nav">
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href=" {{ route('search') }} ">Search for friends</a></li>
                                <li><a href="{{ route('requests') }}">View friend requests</a></li>
                            </ul>
                        </nav>
                    </div>

                    <div id="content" class="col-md-9">
                        @if (isset($friends) && count($friends) > 0)
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
                                                    <strong>{{ $friend['firstname'] }}</strong>
                                                    <strong>{{ $friend['lastname'] }}</strong>
                                                </td>

                                                @if($friend['confirmed'] === true)
                                                    <td>
                                                        <strong>CONFIRMED</strong>
                                                    </td>
                                                @else
                                                    <td>
                                                        <strong>PENDING</strong>
                                                    </td>
                                                @endif


                                                    <td>
                                                        <form action="{{url('/friend/' . $friend['id'])}}" method="POST">
                                                            {{ method_field('DELETE') }}
                                                            {{ csrf_field() }}

                                                            <button type="submit" id="delete-friend-{{ $friend['id'] }}" class="btn btn-danger">
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