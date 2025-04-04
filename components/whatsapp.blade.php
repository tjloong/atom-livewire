@php
$number = $attributes->get('number');
$text = $attributes->get('text');
$url = collect([
    'https://wa.me/'.$number,
    $text ? 'text='.$text : null,
])->filter()->join('?');
@endphp

<a
href="{{ $url }}"
target="_blank"
class="fixed z-90 right-14 bottom-14 bg-green-500 rounded-full shadow flex items-center justify-center gap-3 py-2 px-5 text-lg text-white transition-transform hover:scale-110">
    <atom:icon whatsapp size="28"/> @t('whatsapp-us')
</a>
