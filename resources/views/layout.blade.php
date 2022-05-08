<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <x-seo :noindex="$noindex ?? false"/>

    <link rel="shortcut icon" href="{{ $favicon ?? asset('storage/img/favicon.png') }}">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css'>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    @if (isset($gfont) && $gfont !== false)
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="preload" as="style" href="{{ $gfont }}">
    @elseif (!isset($gfont) && app()->isLocale('zh-my'))
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@100;300;400;500;700;900&display=swap">
    @elseif (!isset($gfont))
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap">
    @endif

    @if ($enabled = $livewire ?? true)
        @livewireStyles
    @endif

    @stack('styles')
    
    {{-- @if ($enabled = $tracking ?? true)
        <x-gtm/>
        <x-ga/>
        <x-fbpixel/>
    @endif --}}
</head>

<body class="font-{{ $fontTheme ?? 'sans' }} antialiased">
    {{-- @if ($enabled = $tracking ?? true)
        <x-gtm noscript/>
        <x-fbpixel noscript/>
    @endif --}}

    @yield('content')

    @if ($enabled = $livewire ?? true)
        @livewireScripts
    @endif
    
    {{-- Vendor scripts --}}
    @foreach ([
        'floating-ui' => [
            'https://unpkg.com/@floating-ui/core@0.1.2/dist/floating-ui.core.min.js',
            'https://unpkg.com/@floating-ui/dom@0.1.2/dist/floating-ui.dom.min.js',
        ],
    ] as $key => $scripts)
        @if (in_array($key, $vendors ?? []))
            @foreach ((array)$scripts as $script)
                <script src="{{ $script }}"></script>
            @endforeach
        @endif
    @endforeach

    @stack('scripts')
</body>
</html>