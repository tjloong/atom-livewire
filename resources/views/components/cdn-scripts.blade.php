@props([
    'cdns' => [
        'chartjs' => [
            'https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js',
        ],
        'colorpicker' => [
            'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/themes/monolith.min.css',
            'https://cdn.jsdelivr.net/npm/@simonwep/pickr/dist/pickr.min.js',
        ],
        'shuffle' => [
            'https://cdn.jsdelivr.net/npm/shufflejs@6.1.0/dist/shuffle.min.js',
        ],
    ],
    'libs' => $attributes->get('libs', []),
])

@foreach ($libs as $name)
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
