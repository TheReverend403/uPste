@extends('admin.master')

@section('title', 'AdminCP - Requests')

@section('admin-content')
    @if (count($users))
        <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>
                        <a href="{{ route('admin.users.accept', ['id' => $user->id]) }}" role="button" class="btn btn-xs btn-success">Accept</a>
                        <a href="{{ route('admin.users.reject', ['id' => $user->id]) }}" role="button" class="btn btn-xs btn-danger">Reject</a>
                    </td>
                </tr>
            @endforeach
        </table>
        </div>
        <div class="text-center">
            {!! $users->render() !!}
        </div>
    @else
        <div class="text-center">
            <div class="alert alert-info">There are no pending account requests.</div>
        </div>
    @endif
@stop