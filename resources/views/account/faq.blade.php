@extends('layouts.account')

@section('title', 'FAQ')

@section('content')
    <div class="container-sm">
        <div class="panel panel-default">
            <div class="panel-heading">
                <b>Why do my images have a different size and hash after I upload them?</b>
            </div>
            <div class="panel-body">
                <p>We strip EXIF data from uploaded images to prevent accidental breaches of user privacy from EXIF tags such as geolocation. This results in a different file size, and obviously a different hash.</p>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <b>How much storage do I have?</b>
            </div>
            <div class="panel-body">
                <blockquote>
                    <p>"Don't take the piss" much.</p>
                    <footer>oneesama</footer>
                </blockquote>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <b>I found a bug or error, where do I report it?</b>
            </div>
            <div class="panel-body">
                <a href="https://github.com/TheReverend403/uPste/issues">https://github.com/TheReverend403/uPste/issues</a>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <b>I found a security flaw, where do I report it?</b>
            </div>
            <div class="panel-body">
                <p>Email
                    <a href="mailto:{{ config('upste.owner_email') }}" title="Send an email to {{ config('upste.owner_name') }}">{{ config('upste.owner_email') }}</a> (<a href="{{ config('upste.owner_gpg') }}">GPG</a>).
                </p>
                <p>Please do not attempt to abuse bugs in the site's security for any purpose beyond reporting the bug as you will be instantly banned for life. Be responsible when other people's security is at risk. Don't be <b>that</b> guy. Nobody likes that guy.</p>
            </div>
        </div>
    </div>
@stop