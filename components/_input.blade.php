@php
$type = $attributes->get('type');
$label = $attributes->get('label');
$prefix = $prefix ?? $attributes->get('prefix');
$suffix = $suffix ?? $attributes->get('suffix');
$caption = $attributes->get('caption');
$invalid = $attributes->get('invalid');
$viewable = $attributes->get('viewable', $type === 'password');
$copyable = $attributes->get('copyable');
$clearable = $attributes->get('clearable');
$placeholder = $attributes->get('placeholder');
$transparent = $attributes->get('transparent');

$icon = [
    'start' => $attributes->get('icon'),
    'end' => $attributes->get('icon-end'),
];

$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;

$size = $attributes->get('size');
$size = $size === 'sm' ? 'h-8 text-sm' : 'h-10';

$classes = $attributes->classes()
    ->add('w-full py-2 text-zinc-700')
    ->add('border border-zinc-200 border-b-zinc-300/80 rounded-lg shadow-sm bg-white')
    ->add('focus:outline-none focus:border-primary group-focus/input:border-primary hover:border-primary-300')
    ->add($invalid ? 'border-red-400' : 'group-has-[[data-atom-error]]/field:border-red-400')
    ->add($type === 'number' ? 'no-spinner' : '')
    ->add(get($icon, 'start') ? 'pl-10' : 'pl-3')
    ->add(get($icon, 'end') ? 'pr-10' : 'pr-3')
    ->add($size)
    ->add('[[data-atom-input-prefix]+[data-atom-input]>&]:rounded-l-none')
    ->add('[[data-atom-input-suffix]+[data-atom-input]>&]:rounded-r-none')
    ->add('[[data-atom-input-tel]>&]:rounded-l-none')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'type' => 'text',
        'step' => $type === 'number' ? 'any' : null,
        'required' => $required,
    ])
    ->except([
        'label', 'caption', 'size', 'icon', 'icon-end',
        'field', 'error', 'placeholder', 'invalid', 'transparent',
        'viewable', 'copyable', 'clearable',
    ])
    ;
@endphp

@if ($label || $caption)
    <atom:_field>
        @if ($label)
            <atom:_label>
                <div class="inline-flex items-center justify-center gap-2">
                    @t($label)
                    @if ($required)
                        <atom:icon asterisk size="12" class="text-red-500 shrink-0"/>
                    @endif
                </div>
            </atom:_label>
        @endif

        <atom:_input :attributes="$attributes->except(['label', 'caption'])"/>
        <atom:_error>@t($error)</atom:_error>
        <atom:caption>@t($caption)</atom:caption>
    </atom:_field>
@elseif ($type === 'file')
    <div>
        file input
    </div>
@elseif ($type === 'tel')
    <div
        x-data="tel({
            code: {{ js($attributes->get('code', '+60')) }},
            @if ($attributes->wire('model')->value())
            value: @entangle($attributes->wire('model')),
            @endif
        })"
        class="group/input">
        <input
            type="hidden"
            x-ref="hidden"
            {{ $attrs->whereStartsWith('wire:model') }}
            {{ $attrs->whereStartsWith('x-model') }}>

        <div class="flex items-center" data-atom-input-tel>
            <div class="relative">
                <select
                    x-ref="options"
                    x-model="code"
                    x-on:change="format()"
                    x-on:input.stop
                    class="appearance-none rounded-l-lg pr-10 pl-3 border border-zinc-200 border-b-zinc-300/80 border-r-0 shadow-sm focus:outline-1 focus:outline-primary {{ $size }}">
                    @foreach (atom()->country()->sortBy('iso_code') as $country)
                        <option value="{{ get($country, 'dial_code') }}">
                            {{ get($country, 'iso_code') }} ({{ get($country, 'dial_code') }})
                        </option>
                    @endforeach
                </select>

                <div class="pointer-events-none absolute top-0 bottom-0 right-0 pr-2 flex items-center justify-center">
                    <atom:icon dropdown/>
                </div>
            </div>

            <input
                type="tel"
                x-model="tel"
                x-on:input.stop="format()"
                placeholder="{{ t($placeholder) }}"
                {{ $attrs->whereDoesntStartWith('wire:model')->whereDoesntStartWith('x-model') }}>
        </div>
    </div>
@elseif ($prefix || $suffix)
    <div class="flex items-center">
        @if ($prefix)
            <div class="rounded-l-lg border border-zinc-200 border-b-zinc-300/80 border-r-0 shadow-sm overflow-hidden {{ $size }}" data-atom-input-prefix>
                @if ($prefix instanceof \Illuminate\View\ComponentSlot)
                    {{ $prefix }}
                @else
                    <div class="px-6 flex items-center justify-center bg-zinc-100 w-full h-full">
                        {{ t($prefix) }}
                    </div>
                @endif
            </div>
        @endif

        @if ($suffix)
            <div class="order-last rounded-r-lg border border-zinc-200 border-b-zinc-300/80 border-l-0 shadow-sm overflow-hidden {{ $size }}" data-atom-input-suffix>
                @if ($suffix instanceof \Illuminate\View\ComponentSlot)
                    {{ $suffix }}
                @else
                    <div class="px-6 flex items-center justify-center bg-zinc-100 w-full h-full">
                        {{ t($suffix) }}
                    </div>
                @endif
            </div>
        @endif

        <atom:_input :attributes="$attributes->except(['prefix', 'suffix'])"/>
    </div>
@else
    <div class="group/input relative w-full block" data-atom-input>
        @if (get($icon, 'start'))
            <div class="z-1 pointer-events-none absolute top-0 bottom-0 flex items-center justify-center text-zinc-400 pl-3 left-0">
                <atom:icon :name="get($icon, 'start')"/>
            </div>
        @endif

        <input placeholder="{{ t($placeholder) }}" {{ $attrs }}>

        @if ($viewable || $copyable || $clearable || get($icon, 'end'))
            <div class="z-1 absolute top-0 bottom-0 flex items-center justify-center text-zinc-400 pr-3 right-0">
                @if ($viewable)
                    <div
                        x-data="{ show: false }"
                        x-init="$watch('show', show => {
                            $el.closest('[data-atom-input]').querySelector('input').setAttribute('type', show ? 'text' : 'password')
                        })"
                        x-on:click.stop="show = !show"
                        class="w-full h-full flex items-center justify-center cursor-pointer">
                        <atom:icon eye-slash x-show="show"/>
                        <atom:icon eye x-show="!show"/>
                    </div>
                @elseif ($copyable)
                    <div
                        x-data="{ copied: false }"
                        x-tooltip="{{ js(t('copy')) }}"
                        x-on:click.stop="() => {
                            let input = $el.closest('[data-atom-input]').querySelector('input')
                            if (!input.value) return

                            $clipboard(input.value)
                                .then(() => copied = true)
                                .then(() => input.select())
                                .then(() => setTimeout(() => copied = false, 1000))
                        }"
                        class="w-full h-full flex items-center justify-center cursor-pointer">
                        <atom:icon copy x-show="!copied"/>
                    </div>
                @elseif ($clearable)
                    <div
                        x-data="{
                            input: null,
                            show: false,
                        }"
                        x-init="() => {
                            input = $el.closest('[data-atom-input]').querySelector('input')
                            input.addEventListener('input', e => show = !empty(e.target.value))
                        }"
                        x-show="show"
                        x-on:click.stop="() => {
                            input.value = ''
                            input.dispatch('input', '')
                            $nextTick(() => input.focus())
                        }"
                        class="w-full h-full flex items-center justify-center cursor-pointer">
                        <atom:icon close size="14"/>
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center pointer-events-none">
                        <atom:icon :name="get($icon, 'end')"/>
                    </div>
                @endif
            </div>
        @endif
    </div>
@endif