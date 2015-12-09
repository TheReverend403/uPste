@extends('account.master')

@section('title', 'My Uploads')

@section('account-content')
    @if(count($uploads))
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <th>Original Name</th>
                <th>URL</th>
                <th>Size</th>
                <th>SHA1</th>
                <th>Uploaded</th>
            </tr>
        @foreach($uploads as $upload)
            <tr>
                <td>{{ $upload->name }}</td>
                <td>{{ $upload->original_name }}</td>
                <td><a href="{{ url($upload->name) }}">{{ url($upload->name) }}</a></td>
                <td>{{ $upload->size }}</td>
                <td>{{ $upload->hash }}</td>
                <td>{{ $upload->created_at }}</td>
            </tr>
        @endforeach
            </table>
    @else
        <div class="text-center alert alert-warning">You don't have any uploads!</div>
    @endif
@stop