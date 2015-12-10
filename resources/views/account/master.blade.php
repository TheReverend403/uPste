@extends('layouts.master')

@section('content')
    @section('nav-left')
        <li><a href="{{ route('account.uploads') }}"><i class="fa fa-upload"></i>&nbsp; Uploads</a></li>
        <li><a href="{{ route('account.resources') }}"><i class="fa fa-file"></i>&nbsp; Resources</a></li>
    @stop
    @section('nav-right')
        @if (Auth::user()->admin)
            <li><a href="{{ route('admin') }}"><i class="fa fa-cog"></i>&nbsp; Admin</a></li>
        @endif
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
               role="button" aria-haspopup="true" aria-expanded="false">
                {{ Auth::user()->name }} &nbsp;<i class="fa fa-caret-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out fa-fw"></i>&nbsp; Logout</a></li>
            </ul>
        </li>
    @stop
    @yield('account-content')

@section('footer')
    <p class="text-muted"><small>Currently hosting {{ DB::table('uploads')->count() }} files for {{ DB::table('users')->where('enabled', true)->count() }} users.</small></p>
@stop
@stop