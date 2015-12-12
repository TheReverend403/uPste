@extends('layouts.admin')

@section('title', 'AdminCP - Uploads: '. $user->name)

@section('content')
    @if(count($uploads))
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <th>URL</th>
                    <th>Size</th>
                    <th>SHA1</th>
                    <th>Uploaded</th>
                </tr>
                @foreach($uploads as $upload)
                    <tr>
                        <td>{{ $upload->original_name }}</td>
                        <td>
                            <a href="{{ env('UPLOAD_URL') . '/' . $upload->name }}">{{ env('UPLOAD_URL') . '/' . $upload->name }}</a>
                        </td>
                        <td>{{ format_bytes($upload->size, 0) }}</td>
                        <td>{{ $upload->hash }}</td>
                        <td>{{ $upload->updated_at }}</td>
                        <td class="text-center">
                            <form action="{{ route('admin.uploads.delete', ['id' => $upload->id]) }}" method="POST">
                                <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                {!! csrf_field() !!}
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="text-center">
            {!! $uploads->render() !!}
        </div>
    @else
        <div class="message-area">
            <div class="alert alert-warning alert-important">{{ $user->name }} doesn't have any uploads!</div>
        </div>
    @endif
@stop