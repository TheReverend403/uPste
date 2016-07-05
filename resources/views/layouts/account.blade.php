@extends('layouts.master')

@section('nav-left')
    <li><a href="{{ route('account.uploads') }}"><i class="fa fa-upload"></i>&nbsp; My Uploads</a></li>
    <li><a href="{{ route('account.resources') }}"><i class="fa fa-file"></i>&nbsp; Resources</a></li>
    <li><a href="{{ route('account.faq') }}"><i class="fa fa-question-circle"></i>&nbsp; FAQ</a></li>
@stop

@section('nav-right')
    <p class="navbar-text">Uploads: {{ Auth::user()->getUploadsCount() }} ({{ Auth::user()->getStorageQuota() }})</p>
    @if (Auth::user()->admin)
        <li><a href="{{ route('admin') }}"><i class="fa fa-cog"></i>&nbsp; Admin</a></li>
    @endif
@stop