@extends('layouts.master')

@section('nav-left')
    @if (config('upste.require_user_approval'))
    <li>
        <a href="{{ route('admin.requests') }}">
            <span class="badge">
                {{ $requestCount }}
            </span>&nbsp; Pending Requests
        </a>
    </li>
    @endif
    <li><a href="{{ route('admin.users') }}"><i class="fa fa-user"></i>&nbsp; Users</a></li>
@stop
