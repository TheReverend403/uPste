@extends('layouts.master')

@section('title', 'Request Account')

@section('content')
    <div class="container-sm jumbotron">
        <form method="POST" action="{{ route('register') }}">
            <div class="form-group">
                <input title="Name" type="text" name="name" class="form-control" placeholder="Username" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <input title="Email" type="email" name="email" class="form-control" placeholder="Email"
                       value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input title="Password" type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-group">
                <input title="Confirm Password" type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">
            </div>
            @if(config('upste.recaptcha_enabled'))
                <div class="form-group">
                    {!! Recaptcha::render() !!}
                </div>
            @endif
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            {!! csrf_field() !!}
        </form>
    </div>
@stop