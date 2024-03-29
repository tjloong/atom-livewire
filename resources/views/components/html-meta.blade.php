@php
    $noindex = $attributes->get('noindex');

    $title = collect([
        !app()->environment('production') ? '['.app()->environment().']' : null,
        config('atom.meta_title') ?? settings('meta_title') ?? config('app.name') ?? '',
    ])->filter()->join(' ');
    
    $description = config('atom.meta_description') ?? settings('meta_description') ?? '';
    $image = config('atom.meta_image') ?? settings('meta_image') ?? '';
    $hreflang = config('atom.hreflang');
    $canonical = config('atom.canonical');
    $jsonld = config('atom.jsonld') ?? [
        '@context' => 'http://schema.org',
        '@type' => 'Website',
        'url' => url()->current(),
        'name' => $title,
    ];
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ strip_tags($description) }}">

@if ($noindex)
<meta name="robots" content="noindex">

@else
<meta property="og:locale" content="en_US">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ strip_tags($description) }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:alt" content="{{ $title }}">
<meta property="og:site_name" content="{{ $title }}">

<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ strip_tags($description) }}">
<meta name="twitter:image" content="{{ $image }}">
<meta name="twitter:image:alt" content="{{ $title }}">

@if ($jsonld)
<script type="application/ld+json">
@json($jsonld)
</script>
@endif

@if ($hreflang)
<link rel="alternate" href="{{ url()->current() }}" hreflang="{{ $hreflang }}" />
@endif

@if ($canonical)
<link rel="canonical" href="{{ $canonical }}" />
@endif
@endif