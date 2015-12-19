@extends('layouts.admin')

@section('title', 'AdminCP - Requests')

@section('content')
    @if ($requestCount)
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->created_at }}</td>
                    <td>
                        <ul class="list-unstyled list-inline list-noborder">
                            <li>
                                <form class="form" action="{{ route('admin.users.accept', ['id' => $user->id]) }}"
                                      method="POST">
                                    <button type="submit" class="btn btn-xs btn-success">Accept</button>
                                    {!! csrf_field() !!}
                                </form>
                            </li>
                            <li>
                                <form class="form" action="{{ route('admin.users.reject', ['id' => $user->id]) }}"
                                      method="POST">
                                    <button type="submit" class="btn btn-xs btn-danger">Reject</button>
                                    {!! csrf_field() !!}
                                </form>
                            </li>
                        </ul>
                    </td>
                </tr>
            @endforeach
            </tbody>
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