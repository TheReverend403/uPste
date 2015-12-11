@extends('layouts.admin')

@section('title', 'AdminCP - Requests')

@section('content')
    @if (count($users))
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Date</th>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td class="text-center">
                            <form class="form" action="{{ route('admin.users.accept', ['id' => $user->id]) }}"
                                  method="POST">
                                <button type="submit" class="btn btn-xs btn-success">Accept</button>
                                {!! csrf_field() !!}
                            </form>
                        </td>
                        <td class="text-center">
                            <form class="form" action="{{ route('admin.users.reject', ['id' => $user->id]) }}"
                                  method="POST">
                                <button type="submit" class="btn btn-xs btn-danger">Reject</button>
                                {!! csrf_field() !!}
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="text-center">
            {!! $users->render() !!}
        </div>
    @else
        <div class="message-area">
            <div class="alert alert-info">There are no pending account requests.</div>
        </div>
    @endif
@stop