@php
$id = $attributes->wire('key')->value() ?: $attributes->wire('model')->value();
$icon = $attributes->get('icon');
$label = $attributes->get('label');
$inline = $attributes->get('inline');
$caption = $attributes->get('caption');
$variant = $attributes->get('variant', 'native');
$prefix = $attributes->get('prefix');
$suffix = $attributes->get('suffix');
$invalid = $attributes->get('invalid');
$multiple = $attributes->get('multiple');
$clearable = $attributes->get('clearable', true);
$searchable = $attributes->get('searchable', false);
$placeholder = $attributes->get('placeholder', 'Please select...');
$transparent = $attributes->get('transparent');

$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;
$enum = $attributes->get('enum');
$options = $attributes->get('options');
$filters = $attributes->get('filters');

$size = $attributes->get('size');
$size = $multiple || $variant === 'listbox'
    ? ($size === 'sm' ? 'min-h-8 text-sm' : 'min-h-10')
    : ($size === 'sm' ? 'h-8 text-sm' : 'h-10');

$classes = $attributes->classes()
    ->add('appearance-none w-full py-2 pr-10 text-zinc-700 text-left')
    ->add('border border-zinc-200 border-b-zinc-300/80 rounded-lg shadow-sm bg-white')
    ->add('focus:outline-none focus:border-primary group-focus/input:border-primary hover:border-primary-300')
    ->add('has-[option.placeholder:checked]:text-zinc-400')
    ->add($invalid ? 'border-red-400' : 'group-has-[[data-atom-error]]/field:border-red-400')
    ->add($icon ? 'pl-10' : 'pl-3')
    ->add($size)
    ->add('[[data-atom-input-prefix]+[data-atom-select-native]>&]:rounded-l-none')
    ->add('[[data-atom-input-suffix]+[data-atom-select-native]>&]:rounded-r-none')
    ;

$attrs = $attributes
    ->class($classes)
    ->merge([
        'required' => $required,
        'wire:key' => $id,
    ])
    ->except([
        'variant', 'label', 'caption', 'size', 'icon', 'icon-end',
        'field', 'error', 'placeholder', 'invalid', 'transparent',
        'options', 'filters', 'searchable',
    ])
    ;
@endphp

@if ($label || $caption)
    <atom:_input.field
        :label="$label"
        :caption="$caption"
        :required="$required"
        :inline="$inline"
        :error="$error">
        <atom:_select
            :placeholder="$placeholder"
            :attributes="$attributes->except(['label', 'caption', 'placeholder', 'inline'])">
            {{ $slot }}
        </atom:_select>
    </atom:_input.field>
@elseif ($prefix || $suffix)
    <atom:_input.prefix :prefix="$prefix" :suffix="$suffix">
        <atom:_select :attributes="$attributes->except(['prefix', 'suffix'])">
            {{ $slot }}
        </atom:_select>
    </atom:_input.prefix>
@elseif ($variant === 'listbox' || $multiple)
    <div
        wire:ignore.self
        x-data="select({
            id: {{ js($id) }},
            name: {{ js($options) }},
            filters: {{ js($filters) }},
            multiple: {{ js($multiple) }},
            @if ($attributes->wire('model')->value())
            value: @entangle($attributes->wire('model')),
            @endif
        })"
        x-modelable="value"
        x-on:keydown.up.prevent.stop="keyUp()"
        x-on:keydown.down.prevent.stop="keyDown()"
        x-on:keydown.enter.prevent.stop="keyEnter()"
        x-on:keydown.space.prevent.stop="keyEnter()"
        x-on:keydown.esc.prevent.stop="close()"
        x-on:click.away="close()"
        data-atom-select-listbox
        class="group/select w-full"
        {{ $attrs->whereDoesntStartWith('wire:model')->except('class') }}>
        <div
            wire:ignore
            x-ref="trigger"
            data-atom-select-listbox-trigger
            class="relative block">
            <button
                type="button"
                x-on:click="open()"
                {{ $attrs->only('class') }}>
                @if ($icon)
                    <div class="z-1 pointer-events-none absolute top-0 bottom-0 flex items-center justify-center text-zinc-400 pl-3 left-0">
                        <atom:icon :name="$icon"/>
                    </div>
                @endif

                <template x-if="isEmpty" hidden>
                    <div class="flex items-center text-zinc-400">
                        {{ t($placeholder) }}
                    </div>
                </template>

                <template x-if="!isEmpty" hidden>
                    @isset ($selected)
                        {{ $selected }}
                    @else
                        <div>
                            <template x-if="multiple" hidden>
                                <div class="-ml-1 w-full flex flex-wrap items-center gap-2">
                                    <template x-for="item in selected" hidden>
                                        <div class="max-w-40 text-sm bg-zinc-100 border rounded pl-2 inline-flex items-center">
                                            <div x-text="item.label" class="truncate"></div>
                                            <div
                                                x-on:click.stop="deselect(item.value)"
                                                class="px-2 shrink-0 flex items-center justify-center cursor-pointer">
                                                <atom:icon close size="12"/>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="!multiple" hidden>
                                <div x-html="selected"></div>
                            </template>
                        </div>
                    @endisset
                </template>

                <template x-if="!visible && loading">
                    <div class="z-1 absolute top-0 bottom-0 pr-3 right-0 text-primary py-3">
                        <atom:icon loading/>
                    </div>
                </template>

                <template x-if="!(!visible && loading)">
                    <div class="z-1 absolute top-0 bottom-0 pr-3 right-0">
                        <div
                            x-show="!isEmpty"
                            x-on:click.stop="clear()"
                            class="w-full h-full flex justify-center cursor-pointer py-3">
                            <atom:icon close size="14"/>
                        </div>

                        <div x-show="isEmpty" class="w-full h-full pointer-events-none py-3">
                            <atom:icon dropdown/>
                        </div>
                    </div>
                </template>
            </button>
        </div>

        <atom:popover
            wire:ignore.self
            x-ref="options"
            x-on:popover-open="setWidth()">
            <atom:menu>
                @if ($options && $searchable)
                    <div class="py-3 px-4 flex items-center gap-2 border-b">
                        <atom:icon search class="text-zinc-400 shrink-0"/>
                        <input
                            type="text"
                            x-ref="search"
                            x-model.debounce.300="text"
                            x-on:input.stop=""
                            class="appearance-none grow w-full focus:outline-none"
                            placeholder="{{ t('search') }}">
                        <div
                            x-show="!loading && text"
                            x-on:click.stop="text = null"
                            class="shrink-0 flex items-center justify-center text-zinc-400 hover:text-zinc-800 cursor-pointer">
                            <atom:icon close/>
                        </div>
                        <div
                            x-show="loading"
                            class="shrink-0 flex items-center justify-center text-primary">
                            <atom:icon loading/>
                        </div>
                    </div>
                @endif

                <ul class="max-h-[300px] overflow-auto">
                    @if ($slot->isNotEmpty())
                        {{ $slot }}
                    @else
                        @forelse ($this->options[$id] ?? [] as $item)
                            <atom:option :option="$item"/>
                        @empty
                            <atom:empty size="sm"/>
                        @endforelse
                    @endif
                </ul>
            </atom:menu>
        </atom:popover>
    </div>
@elseif ($variant === 'native')
    <div
        wire:ignore.self
        {{ $attrs->only('wire:key') }}
        @if ($options)
        x-data
        x-init="$wire.getOptions({{ js($id) }}, {{ js($options) }}, {{ js($filters) }})"
        @endif
        class="group/input relative w-full block"
        data-atom-select-native>
        @if ($icon)
            <div class="z-1 pointer-events-none absolute top-0 bottom-0 flex items-center justify-center text-zinc-400 pl-3 left-0">
                <atom:icon :name="$icon" size="16"/>
            </div>
        @endif

        <select {{ $attrs->except('wire:key') }}>
            @if ($placeholder)
                <atom:option value="" selected class="placeholder">
                    {{ t($placeholder) }}
                </atom:option>
            @endif

            @forelse ($this->options[$id] ?? [] as $option)
                <atom:option :value="get($option, 'value')">
                    {!! t(get($option, 'label')) !!}
                </atom:option>
            @empty
                {{ $slot }}
            @endforelse
        </select>

        @if ($clearable)
            <div
                x-cloak
                x-data="{
                    show: false,
                    select: null,
                }"
                x-init="() => {
                    select = $el.parentNode.querySelector('select')
                    select.addEventListener('change', (e) => show = !empty(e.target.value))
                }"
                x-on:click.stop="() => {
                    select.value = ''
                    select.dispatch('change')
                }"
                x-bind:class="!show && 'pointer-events-none'"
                class="z-1 absolute top-0 bottom-0 flex items-center justify-center pr-3 right-0">
                <atom:icon close x-show="show"/>
                <atom:icon dropdown x-show="!show"/>
            </div>
        @else
            <div class="z-1 pointer-events-none absolute top-0 bottom-0 flex items-center justify-center pr-3 right-0">
                <atom:icon dropdown/>
            </div>
        @endif
    </div>
@endif