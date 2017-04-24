<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="author" content="staraw">
    <meta name="description" content="世达奥科">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="shortcut icon" href="/favicon.ico" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Wemesh') }}</title>
    <link href={{mix('css/app.css')}} rel="stylesheet">
    <script>
        window.Wemesh = {csrfToken: "{{ csrf_token() }}", id: "{{ Auth::id()}}"}
        window.app = "{{config('app.name', 'Wemesh')}}"
    </script>
</head>
<body class="skin-blue fixed">
    <div id="app"></div>
    <script src={{mix('js/app.js')}}></script>
    @yield('scripts')
</body>
</html>
