<div class="well">
    <p><b>Welcome to {{ env('DOMAIN') }}, {{ Auth::user()->name }}. Here's everything you need to know.</b></p>
    <ol>
        <li>For support, email <a href="mailto:{{ env('OWNER_EMAIL') }}">{{ env('OWNER_EMAIL') }}</a>.</li>
        <li>For support or social, hang out in {{ env('IRC_CHANNEL') }} on {{ env('IRC_SERVER') }}.</li>
        <li>All uploads are visible to the public. Only members may upload.</li>
        <li>Upload limit is {{ env('UPLOAD_LIMIT') }}.</li>
        <li>Do not upload child porn or malware, you'll be banned without mercy.</li>
        <li>Some extra stuff is provided for members, <a href="{{ route('account.resources') }}">{{ route('account.resources') }}</a>.</li>
    </ol>
    <p>This message goes away tomorrow. Enjoy your stay.</p>
</div>
<hr>