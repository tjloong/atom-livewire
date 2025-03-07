@php
$html = atom()->html()
    ->noindex(app()->environment('staging') || $attributes->get('noindex') || $attributes->get('no-index'))
    ->analytics($attributes->get('analytics'))
    ->get()
    ;

$vite = $attributes->get('vite') ?: [
    'resources/js/'.request()->portal().'.js',
    'resources/css/'.request()->portal().'.css',
];

$attrs = $attributes
    ->class(['font-sans antialiased has-[[data-atom-sheet][data-open]]:overflow-hidden'])
    ->except(['noindex', 'no-index', 'analytics', 'cdn', 'vite'])
    ;
@endphp

<!DOCTYPE html>
<html lang="{{ $html->lang }}">
<head>
<title>{{ $html->title }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $html->description }}">
<meta name="application-meta" content="application-meta" data-recaptcha-sitekey="{{ $html->recaptcha }}">

@if ($html->noindex)
<meta name="robots" content="noindex">
@else
<meta property="og:locale" content="en_US">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $html->title }}">
<meta property="og:description" content="{{ $html->description }}">
<meta property="og:image" content="{{ $html->image }}">
<meta property="og:image:alt" content="{{ $html->title }}">
<meta property="og:site_name" content="{{ $html->title }}">
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $html->title }}">
<meta name="twitter:description" content="{{ $html->description }}">
<meta name="twitter:image" content="{{ $html->image }}">
<meta name="twitter:image:alt" content="{{ $html->title }}">
@stack('meta')

@if ($html->jsonld)<script type="application/ld+json">@json($html->jsonld)</script>@endif
@if ($html->hreflang)<link rel="alternate" href="{{ url()->current() }}" hreflang="{{ $html->hreflang }}"/>@endif
@if ($html->canonical)<link rel="canonical" href="{{ $html->canonical }}" />@endif
@endif

@if ($html->favicon)
<link rel="icon" type="{{ $html->favicon->mime }}" href="{{ $html->favicon->url }}">
@endif

@vite ($vite)

@if ($html->gtm)
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $html->gtm }}');</script>
<!-- End Google Tag Manager -->
@endif

@if ($html->ga)
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $html->ga }}"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
</script>
@endif

@if ($html->fbp)
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
fbq('init', '{{ $html->fbp }}');
fbq('track', 'PageView');
</script>
<!-- End Facebook Pixel Code -->
@endif

@if (($modals = session()->pull('__modals'))) {
<script>
document.addEventListener('alpine:initialized', () => {
    let modals = {{ js($modals) }}
    Object.keys(modals).forEach(name => {
        let action = modals[name].action
        let data = modals[name].data
        Atom.modal(name)[action](data)
    })
})
</script>
@endif

@if ($sheet = session()->pull('__sheet'))
<script>
document.addEventListener('alpine:initialized', () => {
    let name = {{ js($sheet['name']) }}
    let action = {{ js($sheet['action']) }}
    let data = {{ js($sheet['data']) }}
    Atom.sheet(name)[action](data)
})
</script>
@endif

@livewireStyles
</head>

<body {{ $attrs }}>
@if ($html->gtm)
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $html->gtm }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif

@if ($html->fbp)
<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ $html->fbp }}&ev=PageView&noscript=1" />
@endif

{{ $slot }}

<div data-tooltip popover="manual">
    <div class="py-1 px-2 pointer-events-none rounded-md bg-black/80 text-zinc-100 shadow text-sm w-max whitespace-nowrap"></div>
</div>
</body>

@if ($gfonts = $attributes->get('gfonts') ?? $html->gfonts)
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ $gfonts }}&display=swap" media="screen">
@endif

<script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/js/all.min.js" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/fontawesome.min.css" media="screen">
@stack('cdn')

@if (atom('route')->has('__lang.js'))
<script src="{{ route('__lang.js') }}"></script>
@endif

@if (atom('route')->has('__icons.js'))
<script src="{{ route('__icons.js') }}"></script>
@endif

@stack('scripts')
@livewireScripts
</html>