<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>We'll be right back.</title>
    <link rel="icon" type="image/png" href="{{ url('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ elixir('assets/css/error.css') }}">
</head>
<body>
<div class="container">
    <div class="content">
        <p>{{ $exception->getMessage() ?: 'Down for maintenance, we\'ll be right back.' }}</p>
    </div>
</div>
</body>
</html>
