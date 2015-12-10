@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
        <form method="POST" action="{{ route('account.password.email') }}">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </div>
            {!! csrf_field() !!}
        </form>
@stop