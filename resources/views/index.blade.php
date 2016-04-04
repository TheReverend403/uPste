@extends('layouts.master')

@section('title', config('upste.site_name'))

@section('content')
    <div class="container-sm text-center jumbotron">
        <h2>Welcome to {{ config('upste.domain') }}</h2>
        <p>{{ config('upste.domain') }} is a {{ config('upste.require_user_approval') ? 'private ' : '' }}file hosting website powered by <a href="https://github.com/TheReverend403/uPste">uPste</a></p>
        @if (config('upste.require_user_approval'))
            @if (config('upste.owner_name') && config('upste.owner_email'))
            <p>Accounts are given with approval from {{ config('upste.owner_name') }} &lt;<a
                        href="mailto:{{ config('upste.owner_email') }}" title="Send an email to {{ config('upste.owner_name') }}">{{ config('upste.owner_email') }}</a>&gt;.</p>
            @endif
        @endif
        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}" class="btn btn-primary">{{ config('upste.require_user_approval') ? 'Request' : 'Register' }} Account</a>
    </div>
@stop
