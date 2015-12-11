@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
    <div class="container-sm">
        <form method="POST" action="{{ route('account.password.reset') }}">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" placeholder="me@mydomain.com"
                       value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Password">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Reset</button>
            </div>
            <input type="hidden" name="token" value="{{ $token }}">
            {!! csrf_field() !!}
        </form>
    </div>
@stop