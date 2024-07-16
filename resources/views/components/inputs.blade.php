@php
$title = $attributes->getAny('title', 'heading');
$cols = $attributes->get('cols') === true ? 2 : $attributes->get('cols', 1);
$nopadding = $attributes->get('no-padding');
$except = ['title', 'heading', 'cols'];
@endphp

<fieldset class="border-t first:border-t-0 {{ $nopadding ? '' : 'p-5' }}">
    @if ($title) <x-heading title="{!! tr($title) !!}" class="mb-5"/> @endif

    <div {{ $attributes->class(array_filter([
        "grid gap-5 md:grid-cols-$cols",
    ]))->except($except) }}>
        {{ $slot }}
    </div>
</fieldset>