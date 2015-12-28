<div class="panel panel-info">
    <div class="panel-heading">
        <h3 class="panel-title">Welcome to {{ config('pste.domain') }}, {{ Auth::user()->name }}. Here's everything you need to
            know.</h3>
    </div>
    <div class="panel-body">
        <div class="text-left">
            <ul>
                <li>For support, email <a href="mailto:{{ config('pste.owner_email') }}" title="Send an email to {{ config('pste.owner_name') }}">{{ config('pste.owner_email') }}</a>.</li>
                <li>For support or social, hang out in {{ config('pste.irc_channel') }} on {{ config('pste.irc_server') }}.</li>
                <li>Max file size per upload is {{ config('pste.upload_limit') }}.</li>
                <li>Do not upload child porn or malware, you'll be banned without mercy.</li>
                <li>Scripts and third-party integrations are provided for members,
                    <a href="{{ route('account.resources') }}">{{ route('account.resources') }}</a>.
                </li>
            </ul>
        </div>
    </div>
    <div class="panel-footer">
        This message will disappear in {{ sprintf(ngettext("%d day", "%d days", $daysRegistered), $daysRegistered) }}.
    </div>
</div>