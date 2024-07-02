@php
$title = $attributes->getAny('title', 'heading');
$cols = $attributes->get('cols', 1);
$multiple = $cols > 1;
$inputs = $attributes->get('inputs', false);
$hover = !$multiple && !$inputs && !$attributes->get('no-hover');
$divide = !$multiple && !$inputs && !$attributes->get('no-divide');
$relaxed = $attributes->get('relaxed');
@endphp

<fieldset class="border-t first:border-t-0 {{ $multiple || $inputs ? 'p-5' : null }}">
    @if ($title) <x-heading title="{!! tr($title) !!}" class="mb-5"/> @endif

    <div {{ $attributes->class(array_filter([
        "grid md:grid-cols-$cols",
        $multiple || $inputs ? 'gap-5' : null,
        !$multiple && !$inputs ? (
            $relaxed ? '*:py-4 *:px-6' : '*:py-2 *:px-4'
        ) : null,
        $divide ? 'divide-y' : null,
        $hover ? 'hover:*:bg-slate-50' : null,
    ])) }}>
        {{ $slot }}
    </div>
</fieldset>