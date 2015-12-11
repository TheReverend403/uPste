@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
    <div class="container-sm jumbotron">
        <form method="POST" action="{{ route('account.password.email') }}">
            <div class="form-group">
                <input title="Email" type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </div>
            {!! csrf_field() !!}
        </form>
    </div>
@stop