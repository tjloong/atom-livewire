<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<x-html-meta :noindex="isset($indexing) ? !$indexing : true"/>

@if ($favicon = collect(['ico', 'png', 'svg', 'jpg'])
    ->filter(fn($ext) => file_exists(storage_path('app/public/img/favicon.'.$ext)))
    ->map(fn($ext) => url('/storage/img/favicon.'.$ext))
    ->first())
<link rel="shortcut icon" href="{{ $favicon }}">
@endif

@if ($enabledAnalytics = $analytics ?? false)
<x-analytics.fathom/>
<x-analytics.gtm/>
<x-analytics.ga/>
<x-analytics.fbpixel/>
@endif

@section('cdn')
@foreach ([
    'dayjs',
    'flatpickr',
    'fontawesome',
    'sortable',
    'alpinejs/anchor',
    'alpinejs/collapse',
    'alpinejs/tooltip',
] as $cdnname)
@cdn($cdnname)
@endforeach
@show

@stack('cdn')

@section('gfont')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ str(app()->currentLocale())->is('zh*') ? 'Noto+Sans+SC' : 'Inter' }}:wght@100;300;400;500;700;900&display=swap">
@show

@vite($vite ?? [
    'resources/css/app.css',
    'resources/js/app.js',
])

@livewireScripts
@livewireStyles

@cdn('alpinejs')

@stack('scripts')
</head>

<body class="font-{{ $fontTheme ?? 'sans' }} antialiased {{ $class ?? '' }}">
@stack('noscripts')

@if ($enabled = $analytics ?? false)
<x-analytics.gtm noscript/>
<x-analytics.fbpixel noscript/>
@endif

<x-notify.alert/>
<x-notify.confirm/>
<x-notify.toast/>
<x-loader/>

@yield('content')
</body>
</html>