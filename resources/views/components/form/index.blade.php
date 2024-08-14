@php
$box = $attributes->get('no-box') ? false : $attributes->get('box', true);
$icon = $attributes->get('icon');
$title = $title ?? $heading ?? $attributes->getAny('title', 'heading');
$submit = $attributes->submitAction();
@endphp

<form
    x-init="$nextTick(() => $el.querySelector('input[autofocus]')?.focus())"
    @if (is_string($submit))
    wire:submit.prevent="{{ $submit }}"
    wire:target="{{ $submit }}"
    @elseif ($submit === false)
    wire:submit.prevent="submit"
    wire:target="submit"
    @endif
    @if ($attributes->wire('loading')->value())
    wire:loading.class="is-loading"
    @endif
    class="group/form relative"
    {{ $attributes->except(['no-box', 'box', 'title', 'heading']) }}>
    <div class="absolute inset-0 z-1 hidden group-[.is-loading]/form:block">
        <div class="absolute top-4 right-4 text-theme">
            <x-spinner size="20"/>
        </div>
    </div>

    @if ($box)
        <x-box>
            @if ($title instanceof \Illuminate\View\ComponentSlot)
                <x-heading no-margin :attributes="$title->attributes->merge(['class' => 'py-4 px-5 rounded-t-lg'])">
                    {{ $title }}
                </x-heading>
            @elseif ($title)
                <x-heading no-margin :title="$title" :icon="$icon" class="py-4 px-5 rounded-t-lg"/>
            @endif

            {{ $slot }}

            @if (isset($foot) && $foot->isNotEmpty())
                <x-slot:foot class="flex items-center gap-3">
                    {{ $foot }}
                </x-slot:foot>
            @elseif (!isset($foot))
                <x-slot:foot>
                    <x-button action="submit"/>
                </x-slot:foot>
            @endif
        </x-box>
    @else
        {{ $slot }}
    @endif
</form>
