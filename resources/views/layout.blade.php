<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-seo :noindex="$noindex ?? false"/>

    <link rel="shortcut icon" href="{{ asset('storage/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">

    @if (app()->isLocale('zh-my'))
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@100;300;400;500;700;900&display=swap">
    @else
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap">
    @endif

    @livewireStyles
    @stack('styles')
    
    @if ($enabled = $tracking ?? true)
        <x-gtm/>
        <x-ga/>
        <x-fbpixel/>
    @endif

    @livewireScripts
    @stack('scripts')
    <script src="{{ mix($script ?? 'js/app.js') }}" defer></script>
</head>

<body class="font-sans antialiased">
    @if ($enabled = $tracking ?? true)
        <x-gtm noscript/>
        <x-fbpixel noscript/>
    @endif

    @yield('content')
</body>
</html>