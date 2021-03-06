<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-seo :noindex="isset($indexing) ? !$indexing : true"/>

    <link rel="shortcut icon" href="{{ 
        $favicon 
        ?? (file_exists(storage_path('app/public/img/favicon.png')) ? asset('storage/img/favicon.png') : null)
        ?? (file_exists(storage_path('app/public/img/favicon.svg')) ? asset('storage/img/favicon.svg') : null)
        ?? (file_exists(storage_path('app/public/img/favicon.jpg')) ? asset('storage/img/favicon.jpg') : null)
    }}">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css'>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @if (isset($gfont) && $gfont !== false)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="{{ $gfont }}">
    @elseif (!isset($gfont) && app()->isLocale('zh-my'))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@100;300;400;500;700;900&display=swap">
    @elseif (!isset($gfont))
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap">
    @endif

    @if ($enabled = $livewire ?? true)
        @livewireStyles
    @endif

    @stack('styles')
    
    @if ($enabled = $analytics ?? false)
        <x-analytics.gtm/>
        <x-analytics.ga/>
        <x-analytics.fbpixel/>
    @endif

    @stack('vendors')
</head>

<body class="font-{{ $fontTheme ?? 'sans' }} antialiased">
    @if ($enabled = $analytics ?? false)
        <x-analytics.gtm noscript/>
        <x-analytics.fbpixel noscript/>
    @endif

    @yield('content')

    @if ($enabled = $livewire ?? true)
        @livewireScripts
    @endif

    @stack('scripts')
</body>
</html>