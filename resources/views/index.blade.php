@extends('layouts.master')

@section('title', 'uPste')

@section('content')
    <div class="container-sm text-center jumbotron">
        <p>{{ config('upste.domain') }} is a private file hosting website.</p>
        <p>Accounts are given with approval from {{ config('upste.owner_name') }} &lt;<a
                    href="mailto:{{ config('upste.owner_email') }}" title="Send an email to {{ config('upste.owner_name') }}">{{ config('upste.owner_email') }}</a>&gt;.</p>
        <p>Your request will <b class="text-danger">NOT</b> be accepted if I don't know you or I'm not expecting your request prior to you making it.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}" class="btn btn-primary">Request Account</a>
    </div>
@stop