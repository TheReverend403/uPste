@extends('layouts.admin')

@section('title', 'AdminCP - Users')

@section('content')
    @if (count($users))
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Registered</th>
                    <th>Updated</th>
                </tr>
                @foreach($users as $user)
                    @if ($user->banned)
                        <tr class="danger">
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
                            @if (!$user->banned)
                                <td class="text-center">
                                    <form class="form" action="{{ route('admin.users.ban', ['id' => $user->id]) }}"
                                          method="POST">
                                        <button type="submit" class="btn btn-xs btn-warning">Ban</button>
                                        {!! csrf_field() !!}
                                    </form>
                                </td>
                            @else
                                <td class="text-center">
                                    <form class="form" action="{{ route('admin.users.unban', ['id' => $user->id]) }}"
                                          method="POST">
                                        <button type="submit" class="btn btn-xs btn-success">Unban</button>
                                        {!! csrf_field() !!}
                                    </form>
                                </td>
                            @endif
                            <td class="text-center">
                                <form class="form" action="{{ route('admin.users.delete', ['id' => $user->id]) }}"
                                      method="POST">
                                    <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                    {!! csrf_field() !!}
                                </form>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.users.uploads', ['id' => $user->id]) }}" role="button"
                                   class="btn btn-xs btn-default">Uploads</a>
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
            <div class="alert alert-danger">There are no users...somehow?</div>
        </div>
    @endif
@stop