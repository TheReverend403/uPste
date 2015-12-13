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
    </script>
    <div class="container-sm text-center">
        @if($new)
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
        <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#api-modal">API Info
        </button>
    </div>

    <div class="modal fade" id="api-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">API</h4>
                </div>
                <div class="modal-body">
                    <p>Your API key is:</p>
                    <pre class="apikey">{{ Auth::user()->apikey }}</pre>
                    <p>This key allows anyone to upload to u.pste.pw as you. Do not lose it. Upload like so:</p>
<pre>curl \
-F key={{ Auth::user()->apikey }} \
-F file=@example.png \
{{ route('api.upload') }}</pre>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('account.resetkey') }}" method="POST">
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-danger">Reset Key</button>
                        </div>
                        {!! csrf_field() !!}
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js" type="application/javascript"></script>
    <script src="{{ url('assets/js/dropzone.js') }}" type="application/javascript"></script>
@stop