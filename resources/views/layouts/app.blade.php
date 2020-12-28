<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @livewireStyles

</head>
<body>
    <div id="app" style="height: 1000px; background-color: #5CBD9D;">
        <nav class="navbar navbar-expand-md navbar-dark py-3 bg-white shadow-sm">
            <div class="container">
                <!-- <h2>{{ config('app.name') }}</h2> -->
            </div>
        </nav>

        <main class="py-5">

            @yield('content')
        </main>

    </div>

     @livewireScripts
</body>
</html>
