<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>

<body>
@yield('content')
</body>
<script src="{{ mix('/js/app.js') }}"></script>
</html>
