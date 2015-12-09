@extends('admin.master')

@section('title', 'AdminCP - Users')

@section('admin-content')
    @if (count($users))
        <div class="table-responsive">
        <table class="table table-bordered">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Updated</th>
                <th>Actions</th>
            </tr>
        @foreach($users as $user)
            @if ($user->banned)
                <tr class="danger">
            @elseif (!$user->enabled)
                <tr class="warning">
            @elseif ($user->admin)
                <tr class="success">
            @else
                <tr>
            @endif
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->created_at }}</td>
                <td>{{ $user->updated_at }}</td>
                <td>
                    @if (!$user->banned)
                        <a href="{{ route('admin.users.ban', ['id' => $user->id]) }}" role="button" class="btn btn-xs btn-warning">Ban</a>
                    @else
                        <a href="{{ route('admin.users.unban', ['id' => $user->id]) }}" role="button" class="btn btn-xs btn-success">Unban</a>
                    @endif
                        <a href="{{ route('admin.users.delete', ['id' => $user->id]) }}" role="button" class="btn btn-xs btn-danger">Delete</a>
                        <a href="{{ route('admin.users.uploads', ['id' => $user->id]) }}" role="button" class="btn btn-xs btn-default">Uploads</a>
                </td>
            </tr>
        @endforeach
        </table>
        </div>
    @else
        <div class="text-center">
            <div class="alert alert-info">There are no users...somehow?</div>
        </div>
    @endif
@stop