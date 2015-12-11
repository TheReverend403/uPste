<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ url('img/favicon.png') }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ url('css/app.css') }}">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    @yield('stylesheets')
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav"
                    aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('index') }}">{{ env('DOMAIN') }}</a>
        </div>

        <div class="collapse navbar-collapse" id="main-nav">
            <ul class="nav navbar-nav">
                @yield('nav-left')
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @yield('nav-right')
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="message-area">
        @if(Session::has('info'))
            <div class="alert alert-success">{{ Session::get('info') }}</div>
        @endif
        @if(Session::has('status'))
            <div class="alert alert-info">{{ Session::get('status') }}</div>
        @endif
        @if(Session::has('warning'))
            <div class="alert alert-warning">{{ Session::get('warning') }}</div>
        @endif
        @if(Session::has('alert'))
            <div class="alert alert-danger">{{ Session::get('alert') }}</div>
        @endif
        @if (count($errors) > 0)
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        @endif
    </div>
    @yield('content')
</div>
<footer class="footer">
    <div class="text-center">
        @yield('footer')
        <p class="text-muted">
            <small>{{ env('IRC_CHANNEL') }} @ {{ env('IRC_SERVER') }}</small>
        </p>
    </div>
</footer>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
@yield('javascript')
</body>
</html>