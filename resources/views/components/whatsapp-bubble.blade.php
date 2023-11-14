@php
    $number = $attributes->get('number');
    $text = $attributes->get('text');
@endphp

<a
    href="https://wa.me/{{ $number }}?text={{ $text }}"
    target="_blank"
    class="fixed z-90 right-14 bottom-14 bg-green-500 rounded-full shadow w-24 h-24 flex items-center justify-center">
    <x-icon name="brands whatsapp" class="text-white text-5xl"/>
</a>