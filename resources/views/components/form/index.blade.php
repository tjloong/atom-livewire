@php
$box = $attributes->get('no-box') ? false : $attributes->get('box', true);
$heading = $title ?? $heading ?? $attributes->get('title') ?? $attributes->get('heading');
$hasSubmitAction = $attributes->hasLike('wire:submit*', 'x-on:submit*', 'x-recaptcha:submit*');
@endphp

<form
    x-cloak
    x-data
    x-init="$nextTick(() => $el.querySelector('input[autofocus]')?.focus())"
    @if (!$hasSubmitAction)
    wire:submit.prevent="submit"
    @endif
    @if (!$hasSubmitAction && $attributes->wire('loading')->value())
    wire:loading.class="is-loading"
    wire:target="submit"
    @endif
    class="group/form relative"
    {{ $attributes->except(['no-box', 'box', 'title', 'heading']) }}>
    <div class="absolute inset-0 hidden group-[.is-loading]/form:block">
        <div class="absolute top-4 right-4 text-theme">
            <x-spinner size="20"/>
        </div>
    </div>

    <div class="{{ $box ? 'bg-white border rounded-xl shadow-sm' : null }}">
        @if ($heading instanceof \Illuminate\View\ComponentSlot)
            <x-heading class="p-4 mb-0" :attributes="$heading->attributes">
                {{ $heading }}
            </x-heading>
        @elseif ($heading)
            <x-heading class="p-4 mb-0" :title="$heading"/>
        @endif

        <div {{ $attributes->only('class') }}>
            {{ $slot }}
        </div>

        <div class="bg-gray-100 p-4 rounded-b-xl">
            @if (isset($foot) && $foot->isNotEmpty())
                {{ $foot }}
            @else
                <x-button action="submit"/>
            @endif
        </div>
    </div>
</form>
