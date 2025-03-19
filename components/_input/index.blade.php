@php
$type = $attributes->get('type');
$label = $attributes->get('label');
$caption = $attributes->get('caption');
$prefix = $attributes->get('prefix');
$suffix = $attributes->get('suffix');
$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;
@endphp

@if ($label || $caption)
    <atom:_input.field
        :label="$label"
        :caption="$caption"
        :required="$required"
        :error="$error">
        <atom:_input :attributes="$attributes->except(['label', 'caption', 'error'])">
            {{ $slot }}
        </atom:_input>
    </atom:_input.field>
@elseif ($prefix || $suffix)
    <atom:_input.prefix :prefix="$prefix" :suffix="$suffix">
        <atom:_input :attributes="$attributes->except(['prefix', 'suffix'])"/>
    </atom:_input.prefix>
@elseif ($type === 'file')
    <atom:_input.file :attributes="$attributes->except('type')">
        {{ $slot }}
    </atom:_input.file>
@elseif ($type === 'tel')
    <atom:_input.tel :required="$required" :attributes="$attributes->except(['type', 'required'])"/>
@elseif ($type === 'color')
    <atom:_input.color :required="$required" :attributes="$attributes->except(['type', 'required'])">
        {{ $slot }}
    </atom:_input.color>
@elseif ($type === 'email' && ($attributes->has('options') || $attributes->get('multiple')))
    <atom:_input.email :requried="$required" :attributes="$attributes->except(['type', 'required'])"/>
@else
    @php
    $size = $attributes->get('size');
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

    $classes = $attributes->classes()
        ->add('w-full py-2 text-zinc-700 no-spinner')
        ->add('border rounded-lg shadow-sm bg-white')
        ->add('focus:outline-none focus:border-primary group-focus/input:border-primary hover:border-primary-300')
        ->add($invalid || $error ? 'border-red-400' : 'border-zinc-200 border-b-zinc-300/80')
        ->add('group-has-[[data-atom-error]]/field:border-red-400')
        ->add($size === 'sm' ? 'h-8 text-sm' : 'h-10')
        ->add(get($icon, 'start') ? 'pl-10' : 'pl-3')
        ->add(get($icon, 'end') ? 'pr-10' : 'pr-3')
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
                        <atom:icon check x-show="copied"/>
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
        @elseif (isset($actions))
            <div {{ $actions->attributes->class('z-1 absolute top-0 bottom-0 flex items-center justify-center text-zinc-400 pr-3 right-0') }}>
                {{ $actions }}
            </div>
        @endif
    </div>
@endif