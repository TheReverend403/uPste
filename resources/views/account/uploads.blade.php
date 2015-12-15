@extends('layouts.account')

@section('title', 'My Uploads')

@section('content')
    @if(count($uploads))
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Size</th>
                    <th>SHA1</th>
                    <th>Uploaded</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($uploads as $upload)
                    <tr>
                        <td>{{ $upload->original_name }}</td>
                        <td>
                            <a href="{{ env('UPLOAD_URL') . '/' . $upload->name }}">{{ env('UPLOAD_URL') . '/' . $upload->name }}</a>
                        </td>
                        <td>{{ format_bytes($upload->size) }}</td>
                        <td>{{ $upload->hash }}</td>
                        <td>{{ $upload->updated_at }}</td>
                        <td>
                            <ul class="list-unstyled list-inline list-noborder">
                                <li>
                                    <form action="{{ route('account.uploads.delete', ['id' => $upload->id]) }}"
                                          method="POST">
                                        <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                        {!! csrf_field() !!}
                                    </form>
                                </li>
                            </ul>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-center">
            {!! $uploads->render() !!}
        </div>
    @else
        <div class="message-area">
            <div class="text-center alert alert-warning alert-important">You don't have any uploads!</div>
        </div>
    @endif
@stop