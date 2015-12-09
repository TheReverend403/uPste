@extends('layouts.master')

@section('title', 'Do you know the way?')

@section('content')
    <div class="text-center">
        <p>u.pste.pw is a private file hosting website.</p>
        <p>Accounts are given with approval from Leliana &lt;<a href="mailto:rev@revthefox.co.uk">rev@revthefox.co.uk</a>&gt;.</p>
        <a href="{{ route('login') }}" role="button" class="btn btn-primary">Login</a>
        <a href="{{ route('register') }}" role="button" class="btn btn-primary">Register</a>
    </div>
@stop