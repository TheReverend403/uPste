@extends('layouts.master')

@section('title', 'Request Account')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        <div class="text-center">
            @if (count($errors) > 0)
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Me" value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" placeholder="me@mydomain.com" value="{{ old('email') }}">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <div class="form-group">
            <label for="password">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Password">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Register</button>
        </div>
        {!! csrf_field() !!}
    </form>
@stop