@extends('layouts.master')

@section('title', 'Do you know the way?')

@section('content')
    <div class="text-center">
        <p>{{ env('DOMAIN') }} is a private file hosting website.</p>
        <p>Accounts are given with approval from {{ env('OWNER_NAME') }} &lt;<a
                    href="mailto:{{ env('OWNER_EMAIL') }}">{{ env('OWNER_EMAIL') }}</a>&gt;.</p>
        <p>Your request will NOT be accepted if I don't know you or I'm not expecting your request prior to you making
            it.</p>
        <p>In other words, random requests are not welcome and will not be accepted.</p>
        <a href="{{ route('login') }}" role="button" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}" role="button" class="btn btn-primary">Request Account</a>
    </div>
@stop