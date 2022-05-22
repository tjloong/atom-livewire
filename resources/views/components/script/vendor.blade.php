@props(['noattr' => empty($attributes->getAttributes())])

@if ($noattr || $attributes->has('floating-ui'))
    <script src="https://unpkg.com/@floating-ui/core@0.7.0"></script>
    <script src="https://unpkg.com/@floating-ui/dom@0.5.0"></script>
@endif

@if ($noattr || $attributes->has('flatpickr'))
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endif

@if ($noattr || $attributes->has('sortable'))
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
@endif