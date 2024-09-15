@php
$lang = (string) str(app()->currentLocale())->replace('_', '-');

$noindex = $attributes->has('noindex')
    ? $attributes->get('noindex')
    : !app()->environment('production') && !current_route('web.*', 'register');

$meta = collect();
$meta->put('title', collect([
    !app()->environment('production') ? '['.app()->environment().']' : null,
    config('atom.meta_title') ?? settings('meta_title') ?? config('app.name') ?? '',
])->filter()->join(' '));
$meta->put('description', config('atom.meta_description') ?? settings('meta_description') ?? '');
$meta->put('description', $meta->get('description') ? strip_tags($meta->get('description')) : '');
$meta->put('image', config('atom.meta_image') ?? settings('meta_image') ?? '');
$meta->put('hreflang', config('atom.hreflang'));
$meta->put('canonical', config('atom.canonical'));
$meta->put('jsonld', config('atom.jsonld') ?? [
    '@context' => 'http://schema.org',
    '@type' => 'Website',
    'url' => url()->current(),
    'name' => $meta->get('title'),
]);

$favicon = collect([
    ['mime' => 'image/png', 'file' => 'favicon.png'],
    ['mime' => 'image/x-icon', 'file' => 'favicon.ico'],
    ['mime' => 'image/jpeg', 'file' => 'favicon.jpg'],
    ['mime' => 'image/svg+xml', 'file' => 'favicon.svg'],
])->first(fn($val) => file_exists(storage_path('app/public/img/'.get($val, 'file'))));

$gfont = $gfont ?? $attributes->get('gfont') ?? (
    str(app()->currentLocale())->is('zh*') 
    ? 'Noto+Sans+SC:wght@100;300;400;500;700;900'
    : 'Inter:wght@100;300;400;500;700;900'
);

$analytics = $attributes->has('analytics')
    ? $attributes->get('analytics')
    : app()->environment('production') && current_route('web.*', 'register', 'onboarding.completed');
$gtm = $analytics ? (settings('gtm_id') ?? config('atom.gtm_id')) : null;
$ga = $analytics ? (settings('ga_id') ?? config('atom.ga_id')) : null;
$fbp = $analytics ? (settings('fbp_id') ?? config('atom.fbp_id')) : null;

$cdnlist = collect([
    'lang' => true,
    'fontawesome' => true,
    'apexcharts' => false,
    'dayjs' => false,
    'flatpickr' => false,
    'ulid' => false,
    'sharer' => false,
    'shuffle' => false,
    'animate' => false,
    'ckeditor' => false,
    'apexcharts' => false,
    'keen-slider' => false,
    'fullcalendar' => false,
    'fullcalendar/google-calendar' => false,
    'alpinejs/mask' => false,
    'alpinejs/sort' => false,
    'alpinejs/anchor' => true,
    'alpinejs/collapse' => true,
    'alpinejs/intersect' => false,
    'alpinejs/autosize' => true,
    'alpinejs/tooltip' => true,
    'alpinejs' => true,
]);
collect(($cdn ?? null)?->attributes?->get('list'))->each(fn($val, $key) => 
    is_string($key) ? $cdnlist->put($key, $val) : $cdnlist->put($val, true)
);
@endphp

<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
<title>{{ $meta->get('title') }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $meta->get('description') }}">
<meta name="application-meta" content="application-meta"
    data-recaptcha-sitekey="{{ settings('recaptcha_site_key') }}">

@if ($noindex)
<meta name="robots" content="noindex">
@else
<meta property="og:locale" content="en_US">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $meta->get('title') }}">
<meta property="og:description" content="{{ $meta->get('description') }}">
<meta property="og:image" content="{{ $meta->get('image') }}">
<meta property="og:image:alt" content="{{ $meta->get('title') }}">
<meta property="og:site_name" content="{{ $meta->get('title') }}">
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $meta->get('title') }}">
<meta name="twitter:description" content="{{ $meta->get('description') }}">
<meta name="twitter:image" content="{{ $meta->get('image') }}">
<meta name="twitter:image:alt" content="{{ $meta->get('title') }}">
@stack('meta')
@if ($jsonld = $meta->get('jsonld'))
<script type="application/ld+json">@json($jsonld)</script>
@endif
@if ($hreflang = $meta->get('hreflang'))
<link rel="alternate" href="{{ url()->current() }}" hreflang="{{ $hreflang }}" />
@endif
@if ($canonical = $meta->get('canonical'))
<link rel="canonical" href="{{ $canonical }}" />
@endif
@endif

@if ($favicon)
<link rel="icon" type="{{ get($favicon, 'mime') }}" href="{{ url('storage/img/'.get($favicon, 'file')) }}">
@endif

@if ($gfont instanceof \Illuminate\View\ComponentSlot)
{{ $gfont }}
@else
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ $gfont }}&display=swap">
@endif

@isset ($vite)
{{ $vite }}
@else
@vite(['resources/js/app.js', 'resources/css/app.css'])
@endisset

@if (($cdn ?? null)?->isNotEmpty())
{{ $cdn }}
@endif

@if ($cdnlist->get('lang'))
<script src="{{ route('__locale', 'js') }}"></script>
@endif
@if ($cdnlist->get('fontawesome'))
@basset("https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/js/all.min.js")
@basset("https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/fontawesome.min.css")
@endif
@if ($cdnlist->get('ckeditor'))
@basset(atom_path('resources/ckeditor/build/ckeditor.js'))
@endif
@if ($cdnlist->get('dayjs'))
@basset('https://cdn.jsdelivr.net/npm/dayjs@1.11.4/dayjs.min.js')
@basset('https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/utc.js')
@basset('https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/timezone.js')
@basset('https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/relativeTime.js')
@endif
@if ($cdnlist->get('flatpickr'))
@basset('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js')
@basset('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css')
@endif
@if ($cdnlist->get('sharer'))
@basset('https://cdn.jsdelivr.net/npm/sharer.js@latest/sharer.min.js')
@endif
@if ($cdnlist->get('shuffle'))
@basset('https://cdn.jsdelivr.net/npm/shufflejs@6.1.0/dist/shuffle.min.js')
@endif
@if ($cdnlist->get('keen-slider'))
@basset('https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/keen-slider.min.js')
@basset('https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/keen-slider.min.css')
@endif
@if ($cdnlist->get('fullcalendar'))
@basset('https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js')
@endif
@if ($cdnlist->get('fullcalendar/google-calendar'))
@basset('https://cdn.jsdelivr.net/npm/@fullcalendar/google-calendar@6.1.13/index.global.min.js')
@endif
@if ($cdnlist->get('animate'))
@basset('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css')
@endif
@if ($cdnlist->get('apexcharts'))
@basset('https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.js')
@basset('https://cdn.jsdelivr.net/npm/apexcharts@3.49.1/dist/apexcharts.min.css')
@endif
@if ($cdnlist->get('ulid'))
@basset('https://cdn.jsdelivr.net/npm/ulid@2.3.0/dist/index.umd.min.js')
@endif
@if ($cdnlist->get('alpinejs/mask'))
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@endif
@if ($cdnlist->get('alpinejs/sort'))
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/sort@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@endif
@if ($cdnlist->get('alpinejs/anchor'))
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/anchor@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@endif
@if ($cdnlist->get('alpinejs/collapse'))
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@endif
@if ($cdnlist->get('alpinejs/intersect'))
@basset('https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@endif
@if ($cdnlist->get('alpinejs/autosize'))
@basset('https://cdn.jsdelivr.net/npm/@marcreichel/alpine-autosize@latest/dist/alpine-autosize.min.js', true, ['defer' => true])
@endif
@if ($cdnlist->get('alpinejs/tooltip'))
@basset('https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@2.0.0/dist/cdn.min.js', true, ['defer' => true])
@basset('https://cdn.jsdelivr.net/npm/tippy.js@6.3.7/dist/tippy.min.css')
@endif
@if ($cdnlist->get('alpinejs'))
@basset('https://cdn.jsdelivr.net/npm/alpinejs@3.13.10/dist/cdn.min.js', true, ['defer' => true])
@endif

@if ($gtm)
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $gtm }}');</script>
<!-- End Google Tag Manager -->
@endif

@if ($ga)
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga }}"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
</script>
@endif

@if ($fbp)
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{{ $fbp }}');
fbq('track', 'PageView');
</script>
<!-- End Facebook Pixel Code -->
@endif

@stack('scripts')

@livewireScripts
@livewireStyles
</head>

<body class="font-sans antialiased">
@if ($gtm)
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtm }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif

@if ($fbp)
<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $fbp }}&ev=PageView&noscript=1" />
@endif

{{ $slot }}
</body>

<x-notify.alert/>
<x-notify.confirm/>
<x-notify.toast/>
<x-loader/>
</html>