@props([
    'id' => component_id($attributes, 'select-input'),
    'model' => $attributes->wire('model')->value(),
    'value' => data_get($this, $attributes->wire('model')->value()),
    'icon' => $attributes->get('icon'),
    'multiple' => $attributes->get('multiple'),
    'isAutocomplete' => $attributes->get('autocomplete', false),
    'placeholder' => $attributes->get('placeholder')
        ? __($attributes->get('placeholder'))
        : (($val = component_label($attributes)) ? __('Select').' '.__($val) : __('Please Select')),
    'isEmpty' => function($val) {
        if (is_array($val)) return empty($val);
        else return is_null($val);
    },
])

<x-form.field {{ $attributes }}>
    <div
        x-cloak 
        x-data="{
            text: null,
            focus: false,
            callback: @js($attributes->has('wire:search') && !$isAutocomplete),
            searchable: @js(!$isAutocomplete && count($options) > 15),
            open () {
                this.focus = true

                this.$nextTick(() => {
                    this.$refs.search?.focus()
                    floatDropdown(this.$refs.anchor, this.$refs.dd)
                })
            },
            search (str) {
                this.$wire.emit('setSelectInputSearch', { id: @js($id), search: str })

                const elems = this.$refs.dd?.querySelectorAll('[data-searchable]')
                if (!elems) return

                Array.from(elems).forEach((elem) => {
                    const searchable = elem.getAttribute('data-searchable')
                    if (!empty(searchable) && searchable.includes(str)) {
                        elem.parentNode.classList.remove('hidden')
                    }
                    else elem.parentNode.classList.add('hidden')
                })
            },
        }"
        x-on:click.away="focus = false" 
        class="relative"
        id="{{ $id }}"
    >
        <div x-ref="anchor"
            x-on:click="open"
            x-bind:class="{ 'active': focus }"
            {{ $attributes->class([
                'form-input w-full flex items-center gap-2',
                'error' => component_error(optional($errors), $attributes),
            ])->only('class') }}
        >
            <div class="flex items-center gap-2 w-full {{ $isEmpty($value) ? 'form-input-caret' : '' }}">
                @if ($icon) <x-icon :name="$icon" class="text-gray-400"/> @endif
    
                @if ($isAutocomplete)
                    <div class="grow" {{ $attributes->wire('model') }}>
                        <input type="text"
                            value="{{ $value }}"
                            x-on:input.debounce.400ms="search($event.target.value)"
                            class="form-input transparent w-full" 
                            placeholder="{{ $placeholder }}" 
                        />
                    </div>
                @elseif (!$isEmpty($value))
                    @isset($selected) {{ $selected }}
                    @else
                        <div class="grow">
                            @if ($multiple)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($value as $val)
                                        <div class="bg-slate-200 rounded-md px-2 text-sm font-medium border border-gray-200 flex items-center gap-2 max-w-[200px]">
                                            <div class="grid">
                                                <div class="truncate text-xs">
                                                    {{ data_get(collect($options)->firstWhere('value', $val), 'label') }}                    
                                                </div>
                                            </div>
                                            <div class="flex" wire:click.stop="$set(@js($model), @js(collect($value)->reject($val)->toArray()))">
                                                <x-close size="12"/>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{ data_get(collect($options)->firstWhere('value', $value), 'label') }}
                            @endif
                        </div>
                    @endif
                @else
                    <input type="text" class="form-input transparent grow" placeholder="{{ $placeholder }}" readonly>
                @endif
    
                @if (!$isEmpty($value)) <x-close wire:click.stop="$set('{{ $model }}', null)"/> @endif
            </div>

            @isset($button) 
                <div class="shrink-0">
                    @if ($button->isNotEmpty()) {{ $button }}
                    @else
                        @php $buttonlabel = $button->attributes->get('label') @endphp
                        @php $buttonicon = $button->attributes->get('icon') @endphp
                        <a {{ $button->attributes->class([
                            'flex items-center justify-center gap-1 rounded-full -mr-1 text-sm',
                            $buttonlabel ? 'px-2 py-0.5' : null,
                            !$buttonlabel && $buttonicon ? 'p-1' : null,
                            $button->attributes->get('class', 'text-gray-800 bg-gray-200'),
                        ]) }}>
                            @if ($buttonicon) <x-icon :name="$buttonicon" size="12"/> @endif
                            {{ __($buttonlabel) }}
                        </a>
                    @endif
                </div>
            @endisset
        </div>

        <div x-ref="dd"
            x-show="focus"
            x-transition.opacity
            class="absolute z-20 bg-white shadow-lg rounded-lg border border-gray-300 overflow-hidden w-full mt-px min-w-[300px] flex flex-col divide-y"
        >
            <div x-show="searchable" class="p-3">
                <input type="text" 
                    x-ref="search" 
                    x-on:input.debounce.300ms="search($event.target.value)"
                    class="form-input w-full" 
                    placeholder="{{ __('Search') }}"
                >
            </div>

            <div class="max-h-[250px] overflow-auto flex flex-col divide-y">
                @if ($slot->isNotEmpty())
                    {{ $slot }}
                @else
                    @forelse ($options as $opt)
                        @if (data_get($opt, 'is_group'))
                            <div class="py-2 px-4 flex items-center gap-3 font-semibold bg-gray-100">
                                @if ($icon = data_get($opt, 'icon')) <x-icon :name="$icon" class="shrink-0 text-gray-500"/> @endif
                                <div class="grow font-semibold">{{ data_get($opt, 'label') }}</div>
                                <x-icon name="chevron-down" class="shrink-0" size="12"/>
                            </div>
                        @else
                            <div 
                                @if ($multiple) 
                                    wire:click="$set(@js($model), @js(collect($value)->push(data_get($opt, 'value'))->unique()->toArray()))"
                                @else
                                    wire:click="$set(@js($model), @js(data_get($opt, 'value')))"
                                @endif
                                x-on:click.debounce.100ms="focus = false"
                                class="py-2 px-4 flex items-center gap-3 cursor-pointer {{ [
                                    'green' => 'bg-green-100 text-green-600 hover:bg-green-300 text-green-800',
                                    'blue' => 'bg-blue-100 text-blue-600 hover:bg-blue-300 text-blue-800',
                                    'orange' => 'bg-orange-100 text-orange-600 hover:bg-orange-300 text-orange-800',
                                ][data_get($opt, 'color')] ?? 'hover:bg-slate-100' }}"
                                id="{{ uniqid() }}"
                            >
                                @if (($avatar = data_get($opt, 'avatar')) || ($avatarPlaceholder = data_get($opt, 'avatar_placeholder')))
                                    <div class="shrink-0">
                                        <x-thumbnail :url="$avatar ?? null" :placeholder="$avatarPlaceholder" size="30" color="random" circle/>
                                    </div>
                                @elseif ($image = data_get($opt, 'image'))
                                    <div class="shrink-0">
                                        <x-thumbnail :url="$image" size="40"/>
                                    </div>
                                @elseif ($flag = data_get($opt, 'flag'))
                                    <div class="shrink-0 w-4 h-4">
                                        <img src="{{ $flag }}" class="w-full h-full object-contain object-center">
                                    </div>
                                @endif

                                <div class="grow grid">
                                    @if (($label = data_get($opt, 'label')) && ($small = data_get($opt, 'small'))) 
                                        <div class="font-medium truncate">{{ $label }}</div> 
                                        <div class="text-gray-500 text-sm truncate">{{ $small }}</div>
                                    @else
                                        <div class="truncate">{{ data_get($opt, 'label') }}</div>
                                    @endif
                                </div>

                                @if (($remark = data_get($opt, 'remark')) || ($status = data_get($opt, 'status')))
                                    <div class="shrink-0 text-right">
                                        @if (!empty($remark)) <div class="text-sm font-medium text-gray-500">{{ $remark }}</div> @endif
                                        @if (!empty($status))
                                            @if (is_array($status))
                                                @foreach ($status as $key => $val)
                                                    <x-badge :label="$val" :color="$key"/>
                                                @endforeach
                                            @else <x-badge :label="$status"/>
                                            @endif
                                        @endif
                                    </div>
                                @endif

                                <div class="hidden" data-searchable="{{ data_get($opt, 'searchable') }}"></div>
                            </div>
                        @endif
                    @empty
                        <x-empty-state title="No options available" subtitle="" size="sm"/>
                    @endforelse
                @endif
            </div>

            @isset($foot)
                @if ($foot->isNotEmpty())
                    {{ $foot }}
                @else
                    <a class="p-4 flex items-center justify-center gap-2" {{ $foot->attributes->except('label', 'icon') }}>
                        @if ($icon = $foot->attributes->get('icon')) <x-icon :name="$icon"/> @endif
                        {{ __($foot->attributes->get('label', '')) }}
                    </a>
                @endif
            @endisset
        </div>
    </div>
</x-form.field>