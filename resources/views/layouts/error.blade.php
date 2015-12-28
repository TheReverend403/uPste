<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="icon" type="image/png" href="{{ url('img/favicon.png') }}">
    <link rel="stylesheet" href="{{ elixir('css/error.css') }}" type="text/css">
</head>
<body>
<div class="container">
    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>
