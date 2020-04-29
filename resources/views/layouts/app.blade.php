<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sape') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
{{-- <body class="bg-gray-200 text-gray-800 antialiased font-sans"> --}}
<body ><div id="app">
    <video
        id="videoBG"
        poster="/imagens/video-bg.jpg"
        autoplay
        muted
        loop
    >
    <source src="/imagens/video-home.mp4" type="video/mp4" />
</video>
    {{-- --}}
    <App  ></App>
       {{-- <main  class="h-screen" --}}
       {{--  --}}
            {{-- @yield('content') --}}
        {{-- </main>--}}
    </div>
</body>
</html>
