@extends('layouts.account')

@section('title', 'My Account')

@section('stylesheets')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/basic.min.css" rel="stylesheet">
@stop

@section('content')
    <script type="application/javascript">
        window.root = "{{ route('index') }}";
        window.api_key = "{{ Auth::user()->apikey }}";
        window.api_upload_url = "{{ route('api.upload') }}";
        window.dropzone_thumbnail = "{{ url('assets/img/thumbnail.png') }}";
        window.max_file_size = "{{ intval(preg_replace('/[^0-9]/', '', config('upste.upload_limit'))) }}";
    </script>
    <div class="container-sm text-center">
        @if($newUser)
            @include('account.new-member')
        @endif
        <div class="panel panel-danger text-left">
            <div class="panel-heading text-center">
                <h3 class="panel-title">WARNING</h3>
            </div>
            <div class="panel-body">
                <p>While only members can upload files, all uploads are visible to the public if they know (or accidentally
                    find) the URL. Therefore, <b class="text-danger">DO NOT</b> upload anything you consider private as we will not accept any
                    responsibility if it gets leaked. If you must upload private files, consider <a href="https://gnupg.org/">encrypting</a> them first.
                </p>
            </div>
        </div>
        <div class="upload">
            <div id="previews" class="dropzone"></div>
            <a id="upload-button" href="#">Drag and drop or click to upload files</a>
        </div>
            <hr>
            <div class="text-left">
                @include('account.api')
            </div>
    </div>
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js" type="application/javascript"></script>
    <script src="{{ elixir('assets/js/dropzone.js') }}" type="application/javascript"></script>
@stop