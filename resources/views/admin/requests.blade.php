@extends('layouts.admin')

@section('title', 'AdminCP - Requests')

@section('content')
    @if ($request_count)
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
                        <div class="row">
                            <div class="col-md-2">
                                <form class="form" action="{{ route('admin.users.accept', ['id' => $user->id]) }}"
                                      method="POST">
                                    <button type="submit" class="btn btn-xs btn-success">Accept</button>
                                    {!! csrf_field() !!}
                                </form>
                            </div>
                            <div class="col-md-2">
                                <form class="form" action="{{ route('admin.users.reject', ['id' => $user->id]) }}"
                                      method="POST">
                                    <button type="submit" class="btn btn-xs btn-danger">Reject</button>
                                    {!! csrf_field() !!}
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="text-center">
            {!! $users->render() !!}
        </div>
    @else
        <div class="message-area">
            <div class="alert alert-info alert-important">There are no pending account requests.</div>
        </div>
    @endif
@stop