@php
$value = $attributes->get('value');
@endphp

<button
type="button"
x-on:click.stop.prevent="() => {
    let html = $el.innerHTML;
    $clipboard($el.getAttribute('data-value'))
        .then(() => $el.innerHTML = @js('<svg xmlns="http://www.w3.org/2000/svg" class="text-green-500 size-4" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-icon lucide-check"><path d="M20 6 9 17l-5-5"/></svg>'))
        .then(() => setTimeout(() => $el.innerHTML = html, 700))
}"
{{ $attributes->merge(['class' => 'flex items-center justify-center gap-2']) }}>
    <atom:icon copy/> {{ $slot }}
</button>
