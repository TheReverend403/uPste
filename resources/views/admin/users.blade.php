@extends('layouts.admin')

@section('title', 'AdminCP - Users')

@section('content')
    @if (count($users))
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Registered</th>
                <th>Uploads</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                @if ($user->banned)
                    <tr class="danger">
                @elseif ($user->admin)
                    <tr class="success">
                @else
                    <tr>
                        @endif
                        <td>
                            <a href="{{ route('admin.users.uploads', $user) }}"
                               title="{{ $user->name }}'s uploads">{{ $user->name }}</a>
                        </td>
                        <td><a href="mailto:{{ $user->email }}"
                               title="Send an email to {{ $user->name }}">{{ $user->email }}</a></td>
                        <td>{{ $user->created_at->copy()->tz(Auth::user()->preferences->timezone) }}</td>
                        <td>{{ Cache::get('uploads_count:' . $user->id) }} ({{ App\Helpers::formatBytes(Cache::get('uploads_size:' . $user->id)) }})</td>
                        <td>
                            <ul class="list-unstyled list-inline list-noborder">
                                @if (!$user->banned)
                                    <li>
                                        <form class="form"
                                              action="{{ route('admin.users.ban', $user) }}"
                                              method="POST">
                                            <button type="submit" class="btn btn-xs btn-warning">Ban</button>
                                            {!! csrf_field() !!}
                                        </form>
                                    </li>
                                @else
                                    <li>
                                        <form class="form"
                                              action="{{ route('admin.users.unban', $user) }}"
                                              method="POST">
                                            <button type="submit" class="btn btn-xs btn-success">Unban</button>
                                            {!! csrf_field() !!}
                                        </form>
                                    </li>
                                @endif
                                <li>
                                    <form class="form"
                                          action="{{ route('admin.users.delete', $user) }}"
                                          method="POST">
                                        <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                        {!! csrf_field() !!}
                                    </form>
                                <li/>
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
            <div class="alert alert-danger">There are no users...somehow?</div>
        </div>
    @endif
@stop