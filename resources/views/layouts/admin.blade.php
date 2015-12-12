@extends('layouts.master')

@section('nav-left')
    <li>
        <a href="{{ route('admin.requests') }}">
            <span class="badge">
                {{ $request_count }}
            </span>&nbsp; Pending Requests
        </a>
    </li>
    <li><a href="{{ route('admin.users') }}"><i class="fa fa-user"></i>&nbsp; Users</a></li>
@stop
