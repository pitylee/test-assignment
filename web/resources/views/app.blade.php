<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MZT test assignment</title>

    @include('partials/head')
</head>

<body>
<div id="app" class="min-h-screen bg-white"></div>

@include('partials/js')
</body>

</html>