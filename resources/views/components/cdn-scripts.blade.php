@foreach ($scripts as $script)
    @if (str()->endsWith($script, '.css'))
        <link rel="stylesheet" href="{{ $script }}">
    @elseif (str()->startsWith($script, 'vite:'))
        @vite([str()->replaceFirst('vite:', '', $script)])
    @else
        <script src="{{ $script }}"></script>
    @endif
@endforeach
