@extends('account.master')

@section('title', 'My Uploads')

@section('account-content')
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
                <td><a href="{{ env('UPLOAD_URL') . '/' . $upload->name }}">{{ env('UPLOAD_URL') . '/' . $upload->name }}</a></td>
                <td>{{ format_bytes($upload->size, 0) }}</td>
                <td>{{ $upload->hash }}</td>
                <td>{{ $upload->created_at }}</td>
            </tr>
        @endforeach
            </table>
         </div>
    @else
        <div class="text-center alert alert-warning">You don't have any uploads!</div>
    @endif
@stop