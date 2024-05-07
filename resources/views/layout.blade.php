@php
$favicon = collect([
    ['mime' => 'image/png', 'file' => 'favicon.png'],
    ['mime' => 'image/x-icon', 'file' => 'favicon.ico'],
    ['mime' => 'image/jpeg', 'file' => 'favicon.jpg'],
    ['mime' => 'image/svg+xml', 'file' => 'favicon.svg'],
])->first(fn($val) => file_exists(storage_path('app/public/img/'.get($val, 'file'))));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->currentLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<x-html-meta :noindex="isset($indexing) ? !$indexing : true"/>

@if ($enabledAnalytics = $analytics ?? false)
<x-analytics.fathom/>
<x-analytics.gtm/>
<x-analytics.ga/>
<x-analytics.fbpixel/>
@endif

@section('vite')
@vite('resources/js/app.js')
@vite('resources/css/app.css')
@show

@if ($favicon)
<link rel="icon" type="{{ get($favicon, 'mime') }}" href="{{ url('storage/img/'.get($favicon, 'file')) }}">
@endif

@section('gfont')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ str(app()->currentLocale())->is('zh*') ? 'Noto+Sans+SC' : 'Inter' }}:wght@100;300;400;500;700;900&display=swap">
@show

@basset("https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/js/all.min.js")
@basset("https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/fontawesome.min.css")

@stack('cdn')
@section('cdn')
@basset('https://cdn.jsdelivr.net/npm/dayjs@1.11.4/dayjs.min.js')
@basset('https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/utc.js')
@basset('https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/relativeTime.js')
@basset('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js')
@basset('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css')
@basset('https://cdn.jsdelivr.net/npm/ulid@2.3.0/dist/index.umd.min.js')
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/sort@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/anchor@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@basset('https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@2.0.0/dist/cdn.min.js', true, ['defer' => true])
@basset('https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy.min.css')
@show

@basset('https://cdn.jsdelivr.net/npm/alpinejs@3.13.10/dist/cdn.min.js', true, ['defer' => true])

@livewireScripts
@livewireStyles

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