<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Laravel</title>

    <link rel="icon" type="image/x-icon" href="{{ mindbox_asset('favicon.ico') }}">
    <link rel="stylesheet" href="{{ mindbox_asset('css/app.css') }}">
    <script src="{{ mindbox_asset('js/app.js') }}" defer></script>
    <script>
        mindbox = window.mindbox || function() { mindbox.queue.push(arguments); };
        mindbox.queue = mindbox.queue || [];
        mindbox('create', {
            endpointId: '{{ config('mindbox.endpointId') }}'
        });
    </script>
    <script src="https://api.mindbox.ru/scripts/v1/tracker.js" async></script>
</head>
<body>
@inertia

</body>
</html>
