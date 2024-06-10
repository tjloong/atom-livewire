@php
$enabled = $attributes->get('enabled') ?? settings('whatsapp_bubble');
$number = $attributes->get('number') ?? settings('whatsapp_number');
$text = $attributes->get('text') ?? settings('whatsapp_text');
@endphp

@if ($enabled && $number)
<a
    href="{{ collect([
        'https://wa.me/'.$number,
        $text ? 'text='.$text : null,
    ])->filter()->join('?') }}"
    target="_blank"
    class="fixed z-90 right-14 bottom-14 bg-green-500 rounded-full shadow flex items-center justify-center gap-3 py-2 px-5 text-lg text-white transition-transform hover:scale-110">
    <x-icon name="brands whatsapp" class="text-2xl"/> {{ tr('app.label.whatsapp-us') }}
</a>
@endif