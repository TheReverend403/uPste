@extends('layouts.master')

@section('title', 'Login')

@section('content')
    <div class="container-sm">
        <form method="POST" action="{{ route('login') }}">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" placeholder="Password">
            </div>
            <div class="text-center">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <a href="{{ route('account.password.email') }}" role="button" class="btn btn-default">Forgot Password</a>
            </div>
            {!! csrf_field() !!}
        </form>
    </div>
@stop