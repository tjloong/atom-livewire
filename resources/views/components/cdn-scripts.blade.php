@props([
    'cdns' => [
        'animate' => [
            'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css',
        ],
        'apexcharts' => [
            'https://cdn.jsdelivr.net/npm/apexcharts',
        ],
        'chartjs' => [
            'https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js',
        ],
        'ckeditor' => [
            '/ckeditor/ckeditor.js',
        ],
        'clipboard' => [
            'https://cdn.jsdelivr.net/npm/clipboard@2.0.10/dist/clipboard.min.js',
        ],
        'colorpicker' => [
            'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css',
            'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js',
        ],
        'dayjs' => [
            'https://cdn.jsdelivr.net/npm/dayjs@1.11.4/dayjs.min.js',
            'https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/utc.js',
            'https://cdn.jsdelivr.net/npm/dayjs@1.11.4/plugin/relativeTime.js',
        ],
        'flip' => [
            'https://cdn.jsdelivr.net/npm/@pqina/flip@1.7.7/dist/flip.min.js',
            'https://cdn.jsdelivr.net/npm/@pqina/flip@1.7.7/dist/flip.min.css',
        ],
        'flatpickr' => [
            'https://cdn.jsdelivr.net/npm/flatpickr',
            'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css',
        ],        
        'recaptcha' => [
            ($sitekey = settings('recaptcha_site_key'))
            ? 'https://www.google.com/recaptcha/api.js?render='.$sitekey
            : null,
        ],
        'shuffle' => [
            'https://cdn.jsdelivr.net/npm/shufflejs@6.1.0/dist/shuffle.min.js',
        ],
        'signature-pad' => [
            'https://cdn.jsdelivr.net/npm/signature_pad@4.1.6/dist/signature_pad.umd.min.js',
        ],
        'social-share' => [
            'https://cdn.jsdelivr.net/npm/sharer.js@latest/sharer.min.js',
        ],
        'sortable' => [
            'https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js',
        ],
    ],
    'libs' => $attributes->get('libs', []),
])

@foreach (array_merge([
    'flatpickr', 
    'dayjs',
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
