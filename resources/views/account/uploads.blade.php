@extends('layouts.account')

@section('title', 'My Uploads')

@section('stylesheets')
    <link href="{{ elixir('assets/css/thumbnailhover.css') }}" rel="stylesheet">
@stop

@section('content')
    @if(count($uploads))
        @foreach ($uploads->chunk(2) as $chunk)
            <div class="row">
                @foreach ($chunk as $upload)
                    <div class="col-xs-6">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="media">
                                    <div class="media-left hidden-sm hidden-xs">
                                        <a href="{{ route('files.get', $upload) }}" class="preview">
                                            <img src="{{ $upload->getThumbnailUrl() }}" class="img-thumbnail" title="{{ $upload->original_name }}" alt="{{ $upload->original_name }}">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="col-lg-10">
                                            <h4 class="media-heading visible-lg">{{ str_limit($upload->original_name, 30) }}</h4>
                                            <h4 class="media-heading visible-md">{{ str_limit($upload->original_name, 25) }}</h4>
                                            <h4 class="media-heading visible-sm">{{ str_limit($upload->original_name, 20) }}</h4>
                                            <h4 class="media-heading visible-xs">{{ str_limit($upload->original_name, 15) }}</h4>
                                            <h5 class="visible-xs visible-sm"><b>URL:</b> <a href="{{ route('files.get', $upload) }}">{{ route('files.get', $upload) }}</a></h5>
                                            <h5><b>Size:</b> {{ App\Helpers::formatBytes($upload->size) }}</h5>
                                            <h5><b>Views:</b> {{ $upload->views }}</h5>
                                            <h5><b>Uploaded:</b> {{ $upload->created_at->copy()->tz(Auth::user()->preferences->timezone) }}</h5>
                                        </div>

                                        <div class="col-lg-2">
                                            <form action="{{ route('account.uploads.delete', $upload) }}" method="POST">
                                                <button type="submit" class="btn btn-danger" title="Delete"><i class="fa fa-remove"></i></button>
                                                {!! csrf_field() !!}
                                            </form>
                                        </div>
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

@section('javascript')
    <script src="{{ elixir('assets/js/thumbnailhover.js') }}" type="application/javascript"></script>
@endsection
