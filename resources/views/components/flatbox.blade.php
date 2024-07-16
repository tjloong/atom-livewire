@php
$heading = $title ?? $heading ?? $attributes->getAny('title', 'heading');
@endphp

<div class="rounded-xl bg-slate-100" {{ $attributes->except(['heading', 'title', 'class']) }}>
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
</div>
