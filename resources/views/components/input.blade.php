@php
$field = $attributes->get('for') ?? $attributes->get('field') ?? $attributes->wire('model')->value();
$size = $attributes->get('size');
$icon = $attributes->get('icon');
$type = $attributes->get('type', 'text');
$label = $attributes->get('label');
$prefix = $prefix ?? $attributes->get('prefix');
$suffix = $suffix ?? $attributes->get('suffix');
$transparent = $attributes->get('transparent');
$placeholder = $attributes->get('placeholder');
$size = $attributes->size('md');
@endphp

<div>
    @if ($label)
        <div class="mb-2">
            <x-label :label="$label" :for="$field"/>
        </div>
    @endif

    <span {{ $attributes
        ->class(array_filter([
            "inline-block leading-normal input-$size has-[:disabled]:opacity-50",

            $transparent
                ? 'bg-transparent'
                : 'px-2 bg-white border border-gray-300 rounded-md hover:ring-1 hover:ring-gray-200',

            $transparent
                ? 'has-[:focus]:border-b-2 has-[:focus]:border-gray-300 has-[:focus]:border-dashed has-[input:readonly]:border-0'
                : 'has-[:focus]:ring-1 has-[:focus]:ring-theme has-[:focus]:ring-offset-1 has-[input:read-only]:ring-0',

            in_array($type, ['text', 'number', 'email']) ? 'h-px' : null,
        ]))
        ->only('class')
    }}>
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @elseif (in_array($type, ['text', 'number', 'email']))
        <span
            x-data="{ clearable: false }"
            x-init="clearable = !empty($refs.input.value)"
            x-on:input="clearable = !empty($refs.input.value)"
            class="px-1 inline-flex items-center gap-2 w-full h-full"
            {{ $attributes->only(['wire:clear', 'x-on:clear']) }}>
            @if ($icon)
                <div class="shrink-0 text-gray-300">
                    <x-icon :name="$icon"/>
                </div>
            @endif

            @if ($prefix instanceof \Illuminate\View\ComponentSlot)
                {{ $prefix }}
            @elseif ($prefix)
                <div class="shrink-0 text-gray-500 font-medium">{!! tr($prefix) !!}</div>
            @endif

            <input type="{{ $type }}"
                x-ref="input"
                placeholder="{!! tr($placeholder) !!}"
                class="grow appearance-none w-full bg-transparent peer"
                {{ $attributes->whereStartsWith('wire:') }}
                {{ $attributes->whereStartsWith('x-') }}>

            <div x-show="clearable" x-on:click.stop="() => {
                $refs.input.value = null
                $refs.input.dispatchEvent(new Event('input', { bubbles: true }))
                $nextTick(() => {
                    $dispatch('clear')
                    $refs.input.focus()
                })
            }" class="shrink-0 cursor-pointer text-gray-400 hover:text-black px-1 peer-disabled:hidden peer-read-only:hidden">
                <x-icon name="xmark"/>
            </div>

            @if ($suffix instanceof \Illuminate\View\ComponentSlot)
                {{ $suffix }}
            @elseif ($suffix)
                <div class="shrink-0 text-gray-500 font-medium">{!! tr($suffix) !!}</div>
            @endif

            @if(isset($button) && $button instanceof \Illuminate\View\ComponentSlot)
                <div class="shrink-0" style="margin-right: -12px;">
                    @if ($button->isNotEmpty())
                        {{ $button }}
                    @else
                        <x-button
                            class="rounded-l-none"
                            :size="$size"
                            :attributes="$button->attributes->except('size')">
                        </x-button>
                    @endif
                </div>
            @endif
        </span>
    @endif
    </span>
</div>
