<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Welcome to {{ env('DOMAIN') }}, {{ Auth::user()->name }}. Here's everything you need to
            know.</h3>
    </div>
    <div class="panel-body">
        <p><b>This message will disappear in {{ $days_registered }} days.</b></p>
        <ol class="text-left">
            <li>For support, email <a href="mailto:{{ env('OWNER_EMAIL') }}">{{ env('OWNER_EMAIL') }}</a>.</li>
            <li>For support or social, hang out in {{ env('IRC_CHANNEL') }} on {{ env('IRC_SERVER') }}.</li>
            <li>Max file size per upload is {{ env('UPLOAD_LIMIT') }}.</li>
            <li>Do not upload child porn or malware, you'll be banned without mercy.</li>
            <li>Scripts and third-party integrations are provided for members,
                <a href="{{ route('account.resources') }}">{{ route('account.resources') }}</a>.</li>
            <li>While only members can upload files, all uploads are visible to the public if they know (or accidentally
                find) the URL. Therefore, <b>DO NOT</b> upload anything you consider private as we will not accept any
                responsibility if it gets leaked.
            </li>
        </ol>
    </div>
</div>