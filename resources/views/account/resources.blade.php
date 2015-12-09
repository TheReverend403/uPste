@extends('account.master')

@section('title', 'My Resources')

@section('account-content')
    <div class="text-center">
        <div class="text-center">
            <h3>Third Party Integrations</h3>
            <p>If you make something neat, <a href="mailto:{{ env('OWNER_EMAIL') }}">let us know</a> and we'll feature it here.</p>
            <h3>Bash script</h3>
            <p>You can upload to {{ env('DOMAIN') }} with our <a href="{{ route('account.script') }}">bash script</a>.</p>
            <h3>More stuff?</h3>
            <p><a href="mailto:{{ env('OWNER_EMAIL') }}">Get in touch</a> if you have any more ideas worth hosting here.</p>
        </div>
    </div>
@stop