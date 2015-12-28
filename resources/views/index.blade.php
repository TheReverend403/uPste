@extends('layouts.master')

@section('title', 'uPste')

@section('content')
    <div class="container-sm text-center jumbotron">
        <p>{{ config('pste.domain') }} is a private file hosting website.</p>
        <p>Accounts are given with approval from {{ config('pste.owner_name') }} &lt;<a
                    href="mailto:{{ config('pste.owner_email') }}" title="Send an email to {{ config('pste.owner_name') }}">{{ config('pste.owner_email') }}</a>&gt;.</p>
        <p>Your request will <b class="text-danger">NOT</b> be accepted if I don't know you or I'm not expecting your request prior to you making it.</p>
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}" class="btn btn-primary">Request Account</a>
    </div>
@stop