@php
    $wire = $attributes->wire('model')->value();
@endphp

<div
    x-cloak
    x-data="{
        tab: null,
        show: false,
        label: null,
    }"
    x-modelable="tab"
    x-init="() => {
        if (@js($wire)) tab = $wire.get(@js($wire))
        $watch('tab', val => $dispatch('input', val))
    }"
    x-on:select-tab.stop="(e) => {
        tab = e.detail.value
        label = e.detail.label
    }"
    class="sidenav flex flex-col"
    {{ $attributes->except('heading') }}>
    @if (isset($heading)) {{ $heading }}
    @else
        @php $heading = $attributes->get('heading') @endphp
        <div x-on:click="show = !show" class="flex items-center gap-3 cursor-pointer mb-4 md:cursor-auto md:mb-0">
            <div class="shrink-0 md:hidden">
                <x-icon name="bars" class="text-gray-500"/>
            </div>
            <div class="grow">
                @if ($heading)
                    <div class="text-xs uppercase font-medium text-gray-500 md:pb-4 md:text-xl md:font-bold md:normal-case md:text-gray-800">
                        {{ tr($heading) }}
                    </div>
                @endif

                <div class="text-lg font-bold md:hidden" x-text="label"></div>
            </div>
        </div>
    @endif

    <div x-show="screensize('sm') ? show : true" x-collapse class="flex flex-col">
        {{ $slot }}
    </div>
</div>
