<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ url('assets/img/favicon.png') }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ elixir('assets/css/global.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    @yield('stylesheets')
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            @if(Auth::check())
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav"
                        aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            @endif
            <a class="navbar-brand" href="{{ route('index') }}">{{ config('upste.domain') }}</a>
        </div>

        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="nav navbar-nav">
                @yield('nav-left')
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @yield('nav-right')
                @if(Auth::check())
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                           role="button" aria-haspopup="true" aria-expanded="false">
                            {{ Auth::user()->name }} &nbsp;<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out fa-fw"></i>&nbsp; Logout</a></li>
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="message-area">
        @include('vendor.flash.message')
    </div>
    @yield('content')
</div>
<footer class="footer">
    <div class="text-center container-sm text-muted">
        <hr>
        @yield('footer')
        <p>
            <small>Currently hosting {{ sprintf(ngettext("%d file", "%d files", $uploadCount), $uploadCount) }} for {{ sprintf(ngettext("%d user", "%d users", $userCount), $userCount) }}, weighing in at {{ Helpers::formatBytes($uploadTotalSize) }}.</small>
        </p>
        <p><small>Powered by <a href="https://github.com/TheReverend403/uPste">uPste</a></small></p>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-2.1.4.min.js" type="application/javascript"></script>
<script src="{{ elixir('assets/js/global.js') }}" type="application/javascript"></script>
@yield('javascript')
</body>
</html>