@extends('layouts.master')

@section('title', 'Login')

@section('content')
    <div class="container-sm jumbotron">
        <form method="POST" action="{{ route('login') }}">
            <div class="form-group">
                <input title="Email" type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input title="Password" type="password" name="password" class="form-control" id="password" placeholder="Password">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="{{ route('account.password.email') }}" class="btn btn-default">Forgot Password</a>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
            </div>
            {!! csrf_field() !!}
        </form>
    </div>
@stop