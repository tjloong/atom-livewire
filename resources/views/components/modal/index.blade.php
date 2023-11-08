@props([
    'id' => component_id($attributes, 'modal'),
    'show' => $attributes->get('show', false),
])

<div
    x-cloak
    x-data="{
        show: false,
        open () { this.show = true },
        close () { this.show = false},
    }"
    x-show="show"
    x-transition.opacity
    x-on:{{ $id }}-open.window="open"
    x-on:{{ $id }}-close.window="close"
    x-on:open.stop="open"
    x-on:close.stop="close"
    @if ($show) x-init="open" @endif
    class="fixed inset-0 z-50 overflow-auto"
    id="{{ $id }}"
    {{ $attributes->wire('open') }}
    {{ $attributes->wire('close') }}
>
    @if ($attributes->get('bg-close', true)) <x-modal.overlay x-on:click="close"/>
    @else <x-modal.overlay/>
    @endif

    <div class="relative mx-auto py-10 px-4 w-max">
        <div class="bg-white rounded-lg shadow-lg flex flex-col divide-y">
            @if (isset($heading) && $heading->isNotEmpty())
                {{ $heading }}
            @elseif (isset($heading))
                <x-heading class="p-4" lg
                    :icon="$heading->attributes->get('icon')"
                    :title="$heading->attributes->get('title')"
                    :subtitle="$heading->attributes->get('subtitle')">
                    <x-close x-on:click.stop="close"/>
                </x-heading>
            @elseif ($heading = $attributes->get('heading'))
                <x-heading class="p-4" lg :title="$heading">
                    <x-close x-on:click.stop="close"/>
                </x-heading>
            @endif

            <div class="grow w-screen {{ $attributes->get('class', 'max-w-screen-sm') }}">
                {{ $slot }}
            </div>

            @isset($foot)
                <div class="shrink-0 p-4 bg-slate-100 rounded-b-lg">
                    {{ $foot }}
                </div>
            @endisset
        </div>
    </div>
</div>
