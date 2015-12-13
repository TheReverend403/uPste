@extends('layouts.admin')

@section('title', 'AdminCP - Users')

@section('content')
    @if (count($users))
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Updated</th>
                <th>Actions</th>
            </tr>
            @foreach($users as $user)
                @if ($user->banned)
                    <tr class="danger">
                @elseif ($user->admin)
                    <tr class="success">
                @else
                    <tr>
                        @endif
                        <td>
                            <a href="{{ route('admin.users.uploads', ['id' => $user->id]) }}" title="{{ $user->name }}'s uploads">{{ $user->name }}</a>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>{{ $user->updated_at }}</td>
                        <td>
                            <div class="row">
                                @if (!$user->banned)
                                    <div class="col-md-2">
                                        <form class="form"
                                              action="{{ route('admin.users.ban', ['id' => $user->id]) }}"
                                              method="POST">
                                            <button type="submit" class="btn btn-xs btn-warning">Ban</button>
                                            {!! csrf_field() !!}
                                        </form>
                                    </div>
                                @else
                                    <div class="col-md-2">
                                        <form class="form"
                                              action="{{ route('admin.users.unban', ['id' => $user->id]) }}"
                                              method="POST">
                                            <button type="submit" class="btn btn-xs btn-success">Unban</button>
                                            {!! csrf_field() !!}
                                        </form>
                                    </div>
                                @endif
                                <div class="col-md-2">
                                    <form class="form"
                                          action="{{ route('admin.users.delete', ['id' => $user->id]) }}"
                                          method="POST">
                                        <button type="submit" class="btn btn-xs btn-danger">Delete</button>
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
            <div class="alert alert-danger">There are no users...somehow?</div>
        </div>
    @endif
@stop