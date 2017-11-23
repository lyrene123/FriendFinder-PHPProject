
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-2 col-sm-8">
            <!-- Current stories with pagination-->
            @if (count($friends) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Friends list
                    </div>
                    @include('common.errors')
                    <div class="panel-body">
                        <table class="table table-striped task-table">
                            <thead>
                            <th>Here are your friends</th>
                            <th>&nbsp;</th>
                            </thead>
                            <tbody>
                            @foreach ($friends as $friend)
                                <tr>
                                    <td class="table-text">
                                        <strong>{{ $friend->firstname }}</strong>
                                        <strong>{{ $friend->lastname }}</strong>
                                    </td>
                                    <!-- Delete Button -->

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
@endsection