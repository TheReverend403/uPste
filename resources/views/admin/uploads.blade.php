@extends('layouts.admin')

@section('title', 'AdminCP - Uploads')

@section('content')
    @if($uploads->count())
        <div class="text-center">
            <h2>{{ $user->name }}'{{ ends_with($user->name, 's') ?: 's' }} Uploads</h2>
            <p>Total: {{ $uploads->count() }} ({{ Helpers::formatBytes($uploads->sum('size')) }})</p>
        </div>
        <hr>
        @foreach ($uploads->chunk(3) as $chunk)
            <div class="row">
                @foreach ($chunk as $upload)
                    <div class="col-xs-4">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="media">
                                    <div class="media-left">
                                        <a href="{{ config('upste.upload_url') . $upload->name }}">
                                            <img src="{{ $upload->getThumbnail() }}" class="img-thumbnail" alt="{{ $upload->original_name }}">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="media-heading">{{ str_limit($upload->original_name, 20) }}</h4>
                                        <h5><b>Size:</b> {{ Helpers::formatBytes($upload->size) }}</h5>
                                        <h5><b>Uploaded:</b><br>{{ $upload->updated_at }}</h5>
                                        <form action="{{ route('account.uploads.delete', ['id' => $upload->id]) }}"
                                              method="POST">
                                            <button type="submit" class="btn btn-block btn-danger">Delete</button>
                                            {!! csrf_field() !!}
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
        <div class="text-center">
            {!! $uploads->render() !!}
        </div>
    @else
        <div class="message-area">
            <div class="alert alert-warning alert-important">{{ trans('messages.admin.no_uploads_found', ['name' => $user->name]) }}</div>
        </div>
    @endif
@stop