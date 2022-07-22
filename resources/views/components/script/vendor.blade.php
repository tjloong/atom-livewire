@props(['noattr' => empty($attributes->getAttributes())])

@if ($noattr || $attributes->has('floating-ui'))
    <script src="https://cdn.jsdelivr.net/npm/@floating-ui/core@1.0.0/dist/floating-ui.core.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.0.0/dist/floating-ui.dom.umd.min.js"></script>
@endif

@if ($noattr || $attributes->has('flatpickr'))
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endif

@if ($noattr || $attributes->has('sortable'))
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endif

@if ($noattr || $attributes->has('swiper'))
    <script src="https://cdn.jsdelivr.net/npm/swiper@8.3.1/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8.3.1/swiper-bundle.min.css">
@endif

@if ($noattr || $attributes->has('chartjs'))
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
@endif