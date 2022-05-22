<a 
    href="{{ $attributes->get('href') }}"
    class="py-1.5 px-3 text-center font-medium {{ $attributes->get('class') ?? 'text-gray-800 hover:text-theme' }}"
>
    @if ($label = $attributes->get('label')) {{ __($label) }}
    @else {{ $slot }}
    @endif
</a>
