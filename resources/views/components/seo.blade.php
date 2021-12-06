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

<script type="application/ld+json">
@json($jsonld)
</script>

@if ($hreflang)
<link rel="alternate" href="{{ url()->current() }}" hreflang="{{ $hreflang }}" />
@endif

@if ($canonical)
<link rel="canonical" href="{{ $canonical }}" />
@endif
@endif