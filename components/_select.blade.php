@php
$id = $attributes->wire('key')->value() ?: $attributes->wire('model')->value();
$icon = $attributes->get('icon');
$label = $attributes->get('label');
$variant = $attributes->get('variant', 'native');
$prefix = $prefix ?? $attributes->get('prefix');
$suffix = $suffix ?? $attributes->get('suffix');
$caption = $attributes->get('caption');
$invalid = $attributes->get('invalid');
$multiple = $attributes->get('multiple');
$clearable = $attributes->get('clearable');
$searchable = $attributes->get('searchable', false);
$placeholder = $attributes->get('placeholder', 'Please select...');
$transparent = $attributes->get('transparent');

$field = $attributes->get('field') ?? $attributes->wire('model')->value();
$required = $attributes->get('required') ?? $this->form['required'][$field] ?? false;
$error = $attributes->get('error') ?? $this->errors[$field] ?? null;
$options = $attributes->get('options');
$filters = $attributes->get('filters');

$size = $attributes->get('size');
$size = $multiple
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
    ])
    ->except([
        'label', 'caption', 'size', 'icon', 'icon-end',
        'field', 'error', 'placeholder', 'invalid', 'transparent',
        'options', 'filters',
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

        <atom:_select
            :placeholder="$placeholder"
            :attributes="$attributes->except(['label', 'caption', 'placeholder'])">
        </atom:_select>

        <atom:_error>@t($error)</atom:_error>
        <atom:caption>@t($caption)</atom:caption>
    </atom:_field>
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

        <atom:_select :attributes="$attributes->except(['prefix', 'suffix'])"/>
    </div>
@elseif ($variant === 'native')
    <div
        x-data
        x-init="$wire.getOptions({{ js($id) }}, {{ js($options) }})"
        class="group/input relative w-full block"
        data-atom-select-native>
        @if ($icon)
            <div class="z-1 pointer-events-none absolute top-0 bottom-0 flex items-center justify-center text-zinc-400 pl-3 left-0">
                <atom:icon :name="$icon"/>
            </div>
        @endif

        <select {{ $attrs }}>
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

        <div class="z-1 pointer-events-none absolute top-0 bottom-0 flex items-center justify-center pr-3 right-0">
            <atom:icon dropdown/>
        </div>
    </div>
@elseif ($variant === 'listbox')
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
        x-on:keydown.up.prevent="keyUp()"
        x-on:keydown.down.prevent="keyDown()"
        x-on:keydown.enter.prevent="keyEnter()"
        x-on:keydown.space.prevent="keyEnter()"
        x-on:keydown.esc.prevent="close()"
        x-on:click.away="close()"
        data-atom-select-listbox
        class="group/select w-full">
        <div class="relative block">            
            <button
                wire:ignore
                type="button"
                x-ref="trigger"
                x-on:click="open()"
                {{ $attrs }}>
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
                                    <template x-for="item in getSelected()" hidden>
                                        <div class="max-w-40 text-sm bg-zinc-100 border rounded pl-2 inline-flex items-center">
                                            <div x-text="item.label" class="truncate"></div>
                                            <div
                                                x-on:click.stop="deselect(item.value)"
                                                class="px-2 shrink-0 flex items-center justify-center cursor-pointer">
                                                <x-icon close size="12"/>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="!multiple && getSelected()" hidden>
                                <div x-html="getSelected()"></div>
                            </template>
                        </div>
                    @endisset
                </template>

                <div class="z-1 absolute top-0 bottom-0 pr-3 right-0">
                    <div
                        x-show="!isEmpty"
                        x-on:click.stop="clear()"
                        class="w-full h-full flex justify-center cursor-pointer {{ $multiple ? 'mt-3' : 'items-center' }}">
                        <atom:icon close size="14"/>
                    </div>

                    <div x-show="isEmpty" class="w-full h-full pointer-events-none flex items-center justify-center">
                        <atom:icon dropdown/>
                    </div>
                </div>
            </button>
        </div>

        <div
            x-ref="options"
            x-show="visible"
            class="absolute z-10 opacity-0 transition-opacity duration-75 rounded-lg bg-white w-full shadow-sm border border-zinc-200">
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
                        x-show="text"
                        x-on:click.stop="text = null"
                        class="shrink-0 flex items-center justify-center text-zinc-400 hover:text-zinc-800 cursor-pointer">
                        <x-icon close/>
                    </div>
                </div>
            @endif

            <ul class="p-1 max-h-[300px] overflow-auto">
                @if ($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    @forelse ($this->options[$id] ?? [] as $item)
                        <atom:option :value="get($item, 'value')" :label="get($item, 'label')"/>
                    @empty
                        <atom:empty size="sm"/>
                    @endforelse
                @endif
            </ul>
        </div>
    </div>
@endif