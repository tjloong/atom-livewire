@props([
    'cdns' => [
        'jquery' => [
            'https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js',
        ],
        'animate' => [
            'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
        ],
        'floating-ui' => [
            'https://cdn.jsdelivr.net/npm/@floating-ui/core@1.0.0/dist/floating-ui.core.umd.min.js',
            'https://cdn.jsdelivr.net/npm/@floating-ui/dom@1.0.0/dist/floating-ui.dom.umd.min.js',
        ],
        'flatpickr' => [
            'https://cdn.jsdelivr.net/npm/flatpickr',
            'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
        ],
        'dayjs' => [
            'https://cdn.jsdelivr.net/npm/dayjs@1.11.4/dayjs.min.js',
            'https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/utc.js',
            'https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/relativeTime.js',
        ],
        'fullcalendar' => [
            'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js',
        ],
        'social-share' => [
            'https://cdn.jsdelivr.net/npm/sharer.js@latest/sharer.min.js',
        ],
        'sortable' => [
            'https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js',
        ],
        'slick' => [
            'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
            'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
        ],
        'swiper' => [
            'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js',
            'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css',
        ],
        'chartjs' => [
            'https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js',
        ],
        'apexcharts' => [
            'https://cdn.jsdelivr.net/npm/apexcharts',
        ],
        'colorpicker' => [
            'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css',
            'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js',
        ],
        'clipboard' => [
            'https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js',
        ],
        'shuffle' => [
            'https://cdn.jsdelivr.net/npm/shufflejs@6.1.0/dist/shuffle.min.js',
        ],
        'flip' => [
            'https://cdn.jsdelivr.net/npm/@pqina/flip@1.7.7/dist/flip.min.js',
            'https://cdn.jsdelivr.net/npm/@pqina/flip@1.7.7/dist/flip.min.css',
        ],
        'recaptcha' => [
            ($sitekey = settings('recaptcha_site_key'))
            ? 'https://www.google.com/recaptcha/api.js?render='.$sitekey
            : null,
        ],
        'signature-pad' => [
            'https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js',
        ],
        'ckeditor' => [
            '/ckeditor/ckeditor.js',
        ],
    ],
    'libs' => $attributes->get('libs', []),
])

@foreach (array_merge([
    'alpinejs', 
    'floating-ui', 
    'flatpickr', 
    'dayjs',
    'jquery',
], $libs) as $name)
    @if ($cdn = collect($cdns)->get($name))
        @foreach ($cdn as $script)
            @if (str()->endsWith($script, '.css'))
                <link rel="stylesheet" href="{{ $script }}">
            @elseif (str()->startsWith($script, 'defer:'))
                <script defer src="{{ str()->replaceFirst('defer:', '', $script) }}"></script>
            @else
                <script src="{{ $script }}"></script>
            @endif
        @endforeach
    @endif
@endforeach
