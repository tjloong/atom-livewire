@foreach ($scripts as $script)
    @if (str()->endsWith($script, '.css'))
        <link rel="stylesheet" href="{{ $script }}">
    @elseif (str()->startsWith($script, 'defer:'))
        <script defer src="{{ str()->replaceFirst('defer:', '', $script) }}"></script>
    @else
        <script src="{{ $script }}"></script>
    @endif
@endforeach
