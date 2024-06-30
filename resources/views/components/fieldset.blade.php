@php
$title = $attributes->getAny('title', 'heading');
$cols = $attributes->get('cols', 1);
$multiple = $cols > 1;
$hover = !$multiple && !$attributes->get('no-hover');
$divide = !$multiple && !$attributes->get('no-divide');
$relaxed = $attributes->get('relaxed');
@endphp

<fieldset class="border-t first:border-t-0 {{ $multiple ? 'p-5' : null }}">
    @if ($title) <x-heading title="{!! tr($title) !!}" class="mb-5"/> @endif

    <div {{ $attributes->class(array_filter([
        'grid',
        $multiple ? "md:grid-cols-$cols gap-5" : (
            $relaxed ? '*:py-4 *:px-6' : '*:py-2 *:px-4'
        ),
        $divide ? 'divide-y' : null,
        $hover ? 'hover:*:bg-slate-50' : null,
    ])) }}>
        {{ $slot }}
    </div>
</fieldset>