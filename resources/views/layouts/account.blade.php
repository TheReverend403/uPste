@extends('layouts.master')

@section('nav-left')
    <li><a href="{{ route('account.uploads') }}"><i class="fa fa-upload"></i>&nbsp; My Uploads</a></li>
    <li><a href="{{ route('account.resources') }}"><i class="fa fa-file"></i>&nbsp; Resources</a></li>
@stop

@section('nav-right')
    @if (Auth::user()->admin)
        <li><a href="{{ route('admin') }}"><i class="fa fa-cog"></i>&nbsp; Admin</a></li>
    @endif
@stop