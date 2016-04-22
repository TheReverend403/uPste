@extends('layouts.account')

@section('title', 'My Account')

@section('stylesheets')
    <link href="{{ elixir('assets/css/dropzone.css') }}" rel="stylesheet">
@stop

@section('content')
    <script type="application/javascript">
        window.root = "{{ route('index') }}";
        window.api_key = "{{ Auth::user()->apikey }}";
        window.api_upload_url = "{{ route('api.upload') }}";
        window.dropzone_thumbnail = "{{ elixir('assets/img/thumbnail.png') }}";
        window.max_file_size = "{{ intval(preg_replace('/[^0-9]/', '', config('upste.upload_limit'))) }}";
    </script>
    <div class="container-sm text-center">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Welcome to {{ config('upste.domain') }}, {{ Auth::user()->name }}. Here's everything you need to
                    know.</h3>
            </div>
            <div class="panel-body">
                <div class="text-left">
                    <ul>
                        <li>For support, email <a href="mailto:{{ config('upste.owner_email') }}" title="Send an email to {{ config('upste.owner_name') }}">{{ config('upste.owner_email') }}</a>.</li>
                        @if (config('upste.irc_server') && config('upste.irc_channel'))
                            <li>For support or social, hang out in {{ config('upste.irc_channel') }} on {{ config('upste.irc_server') }}.</li>
                        @endif
                        <li>Max file size per upload is {{ App\Helpers::formatBytes(config('upste.upload_limit')) }}.</li>
                        <li>Do not upload child porn or malware, you'll be banned without mercy.</li>
                        <li>Scripts and third-party integrations are provided for members,
                            <a href="{{ route('account.resources') }}">{{ route('account.resources') }}</a>.
                        </li>
                    </ul>
                </div>
                <hr>
                <div class="panel panel-danger panel-no-margin text-left">
                    <div class="panel-heading text-center">
                        <h3 class="panel-title">WARNING</h3>
                    </div>
                    <div class="panel-body">
                        <p>While only members can upload files, all uploads, as well as their original names, are visible to the public if they know (or accidentally
                            find) the URL. Therefore, <b class="text-danger">DO NOT</b> upload anything you consider private as we will not accept any
                            responsibility if it gets leaked. If you must upload private files, consider <a href="https://gnupg.org/">encrypting</a> them first.
                        </p>
                        <p><b>tl;dr All uploads should be considered public.</b></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="upload">
            <div id="previews" class="dropzone"></div>
            <a id="upload-button" href="#">Drag and drop or click to upload files</a>
        </div>
        <button class="btn btn-block btn-primary" type="button" data-toggle="collapse" data-target="#collapseApiInfo" aria-expanded="false" aria-controls="collapseApiInfo">
            Toggle API Info
        </button>
        <div id="collapseApiInfo" class="text-left collapse">
            <hr>
            @include('account.api')
        </div>
    </div>
@stop

@section('javascript')
    <script src="{{ elixir('assets/js/dropzone.js') }}" type="application/javascript"></script>
@stop
