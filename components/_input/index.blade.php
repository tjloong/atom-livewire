@php
$type = $attributes->get('type');
$size = $attributes->get('size');
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$viewable = $attributes->get('viewable', $type === 'password');
$copyable = $attributes->get('copyable');
$clearable = $attributes->get('clearable');
$placeholder = $attributes->get('placeholder');

$icon = [
    'start' => $attributes->get('icon'),
    'end' => $attributes->get('icon-end'),
];

$classes = $attributes->classes()
    ->add('w-full py-2 text-zinc-700')
    ->add('border border-zinc-200 border-b-zinc-300/80 rounded-lg shadow-sm bg-white')
    ->add('focus:outline-none focus:border-primary group-focus/input:border-primary hover:border-primary-300')
    ->add($type === 'number' ? 'no-spinner' : '')
    ->add(get($icon, 'start') ? 'pl-10' : 'pl-3')
    ->add(get($icon, 'end') ? 'pr-10' : 'pr-3')
    ->add($size === 'sm' ? 'h-8 text-sm' : 'h-10')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'type' => 'text',
        'step' => $type === 'number' ? 'any' : null,
    ])
    ->except(['label', 'caption', 'size', 'icon', 'icon-end', 'placeholder', 'viewable', 'copyable', 'clearable'])
    ;
@endphp

@if ($label || $caption)
    <atom:_field>
        @if ($label)
            <atom:_label>@t($label)</atom:_label>
        @endif

        <atom:_input :attributes="$attributes->except(['label', 'caption'])"/>

        <atom:caption>@t($caption)</atom:caption>
    </atom:_field>
@elseif ($type === 'file')
    <atom:_input.file :attributes="$attributes">
        {{ $slot }}
    </atom:_input.file>
@elseif ($type === 'button')
    <button class="group/input relative w-full block focus:outline-none" data-atom-input>
        @if (get($icon, 'start'))
            <div class="z-1 pointer-events-none absolute top-0 bottom-0 flex items-center justify-center text-zinc-400 pl-3 left-0">
                <atom:icon :name="get($icon, 'start')"/>
            </div>
        @endif

        <div {{ $attrs }}>
            @if ($slot->isEmpty())
                <div class="text-zinc-400 text-left">
                    {{ t($placeholder) }}
                </div>
            @else
                {{ $slot }}
            @endif
        </div>

        @if (get($icon, 'end'))
            <div class="z-1 pointer-events-none absolute top-0 bottom-0 flex items-center justify-center text-zinc-400 pr-3 right-0">
                <atom:icon :name="get($icon, 'end')"/>
            </div>
        @endif
    </button>
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
                        <atom:icon close/>
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