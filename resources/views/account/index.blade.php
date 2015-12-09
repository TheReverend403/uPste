@extends('account.master')

@section('title', 'My Account')

@section('stylesheets')
    <link href="//cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/basic.min.css" rel="stylesheet">
@stop

@section('account-content')
    <script>
        window.root = "{{ route('index') }}";
        window.api_key = "{{ Auth::user()->apikey }}";
    </script>
    <div class="text-center">
        @if($new)
            @include('account.new-member')
        @endif
        <div class="upload">
            <a class="upload-button" href="#">Drag and drop or click to upload files</a>
        </div>
            <div id="previews" class="dropzone">
            </div>
        <hr>
        <button type="button" class="btn btn-block btn-primary" data-toggle="modal" data-target="#api-modal">API Info</button>
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
                    <p>This key allows anyone to upload to u.pste.pw as you. Do not lose it. Upload like
                        so:</p>
                    <pre>curl \
        -F key={{ Auth::user()->apikey }} \
        -F file=@example.png \
                        {{ route('api.upload') }}</pre>
                    <p>If your key is compromised, press "Reset Key" below.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('account.resetkey') }}" class="btn btn-danger" role="button">Reset Key</a>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Dismiss</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="//cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js" type="application/javascript"></script>
    <script src="{{ url('js/dropzone_config.js') }}" type="application/javascript"></script>
@stop