@extends('admin.master')

@section('title', 'AdminCP - Requests')

@section('admin-content')
    @if (count($users))
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td><a href="{{ route('admin.users.enable', ['id' => $user->id]) }}" role="button" class="btn btn-xs btn-success">Enable</a></td>
                </tr>
            @endforeach
        </table>
    @else
        <div class="text-center">
            <div class="alert alert-info">There are no users awaiting approval.</div>
        </div>
    @endif
@stop