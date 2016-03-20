@extends('layouts.account')

@section('title', 'My Uploads')

@section('content')
    @if(count($uploads))
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
                                        <h4 class="media-heading">{{ str_limit($upload->original_name, 15) }}</h4>
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
            <div class="text-center alert alert-warning alert-important">{{ trans('messages.no_uploads_found') }}</div>
        </div>
    @endif
@stop