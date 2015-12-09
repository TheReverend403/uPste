@extends('layouts.master')

@section('content')
    @section('nav-left')
        <li><a href="{{ route('admin.requests') }}"><i class="fa fa-user-plus"></i>&nbsp; Pending Accounts</a></li>
        <li><a href="{{ route('admin.users') }}"><i class="fa fa-user"></i>&nbsp; Users</a></li>
    @stop
    @section('nav-right')
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
               role="button" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name }} &nbsp;<i class="fa fa-caret-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out fa-fw"></i>&nbsp; Logout</a></li>
            </ul>
        </li>
    @stop
    @yield('admin-content')
@stop