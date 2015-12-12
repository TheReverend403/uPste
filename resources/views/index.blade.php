@extends('layouts.master')

@section('title', 'uPste')

@section('content')
    <div class="container-sm text-center jumbotron">
        <p>{{ env('DOMAIN') }} is a private file hosting website.</p>
        <p>Accounts are given with approval from {{ env('OWNER_NAME') }} &lt;<a
                    href="mailto:{{ env('OWNER_EMAIL') }}">{{ env('OWNER_EMAIL') }}</a>&gt;.</p>
        <p>Your request will <b class="text-danger">NOT</b> be accepted if I don't know you or I'm not expecting your request prior to you making it.</p>
        <a href="{{ route('login') }}" role="button" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}" role="button" class="btn btn-primary">Request Account</a>
    </div>
@stop