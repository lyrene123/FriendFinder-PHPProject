@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
                <div>
                    @if (Auth::check())
                        <section><a href=" {{ route('friends') }} ">Manage your friends</a></section>
                        <section><a href=" {{ route('coursemanager') }}">Manage your courses</a></section>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
