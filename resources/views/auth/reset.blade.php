@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
    <form method="POST" action="{{ route('account.password.reset') }}">
        <div class="text-center">
            @if (count($errors) > 0)
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
        </div>
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
@stop