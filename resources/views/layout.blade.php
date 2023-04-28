<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-seo :noindex="isset($indexing) ? !$indexing : true"/>

    <link rel="shortcut icon" href="{{ 
        $favicon 
        ?? (file_exists(storage_path('app/public/img/favicon.ico')) ? asset('storage/img/favicon.ico') : null)
        ?? (file_exists(storage_path('app/public/img/favicon.png')) ? asset('storage/img/favicon.png') : null)
        ?? (file_exists(storage_path('app/public/img/favicon.svg')) ? asset('storage/img/favicon.svg') : null)
        ?? (file_exists(storage_path('app/public/img/favicon.jpg')) ? asset('storage/img/favicon.jpg') : null)
    }}">
    
    @if (isset($gfont) && $gfont !== false)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="{{ $gfont }}">
    @elseif (!isset($gfont) && str(app()->currentLocale())->is('zh*'))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@100;300;400;500;700;900&display=swap">
    @elseif (!isset($gfont))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap">
    @endif

    @if ($enabledLivewire = $livewire ?? true)
        @livewireStyles
    @endif

    @if ($enabledAnalytics = $analytics ?? false)
        <x-analytics.ja/>
        <x-analytics.gtm/>
        <x-analytics.ga/>
        <x-analytics.fbpixel/>
    @endif

    <x-cdn-scripts :scripts="array_filter($cdn ?? [])"/>

    @vite(array_merge(
        ['resources/css/app.css'],
        $vite ?? ['resources/js/app.js'],
    ))

    @stack('styles')
</head>

<body class="font-{{ $fontTheme ?? 'sans' }} antialiased {{ $class ?? '' }}">
    @if ($enabled = $analytics ?? false)
        <x-analytics.gtm noscript/>
        <x-analytics.fbpixel noscript/>
    @endif

    <x-popup/>
    <x-loader/>

    @yield('content')

    @if ($enabledLivewire = $livewire ?? true)
        @livewireScripts
    @endif
</body>
</html>