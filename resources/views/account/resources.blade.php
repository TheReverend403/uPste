@extends('layouts.account')

@section('title', 'My Resources')

@section('content')
    <div class="container-sm">
        <div class="text-center">
            <h3>Third Party Integrations</h3>
            <p>If you make something neat, <a href="mailto:{{ config('pste.owner_email') }}">let us know</a> and we'll feature it
                here.</p>
            <h3>Bash script</h3>
            <p>You can upload to {{ config('pste.domain') }} with our bash script.</p>
            <p>This script depends on <a href="https://github.com/naelstrof/slop">slop</a> and <a
                        href="https://github.com/naelstrof/maim">maim</a>.</p>
            <p>Save this to <code>~/.config/pstepw</code>:</p>
        </div>
    <pre>
#!/bin/bash

# DO NOT SHARE YOUR KEY
key={{ Auth::user()->apikey }}
# Log URLs
log=false
# Log file location (if relevant)
logfile="$HOME/.pstepw"
# Copy links to clipboard after upload (requires xclip)
clipboard=true
# Send a notification when done
notify=true
# Open URL in browser
browser=true
</pre>
        <div class="text-center">
            <p>Save this to any location in your <code>$PATH</code>.</p>
            <p>(<a href="{{ route('account.resources.bash') }}">plaintext version</a>)</p>
        </div>
        <pre>@include('account.resources.bash')</pre>
    </div>
@stop