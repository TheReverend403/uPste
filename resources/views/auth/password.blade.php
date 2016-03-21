@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
    <div class="container-sm jumbotron">
        <form method="POST" action="{{ route('account.password.email') }}">
            <div class="form-group">
                <input title="Email" type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
            </div>
            @if(config('upste.recaptcha_enabled'))
                <div class="form-group">
                    {!! Recaptcha::render() !!}
                </div>
            @endif
            <div class="text-right">
                <button type="submit" class="btn btn-primary">Send Reset Link</button>
            </div>
            {!! csrf_field() !!}
        </form>
    </div>
@stop