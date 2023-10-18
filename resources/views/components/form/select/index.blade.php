@php
    $multiple = $attributes->get('multiple', false);
    $searchable = $attributes->get('searchable', true);
    $value = data_get($this, $attributes->wire('model')->value());
    $empty = (is_array($value) && empty($value)) || is_null($value);
    $placeholder = __($attributes->get('placeholder') ?? (
        component_label($attributes) ? collect(['Select', component_label($attributes)])->join(' ') : 'Please Select'
    ));
@endphp

<x-form.field {{ $attributes }}>
    <div
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            searchText: @entangle('selectInputSearchText'),
            multiple: @js($attributes->get('multiple', false)),
            show: false,
            open () {
                if (this.multiple && !this.value) this.value = []
                
                this.show = true
                this.$nextTick(() => {
                    $el.querySelector('#select-input-search')?.focus()
                    floatDropdown(this.$refs.anchor, this.$refs.dd)
                })
            },
            close () {
                this.show = false
                this.searchText = null
            },
            select (val) {
                if (this.multiple) {
                    if (this.value.indexOf(val) === -1) this.value.push(val)
                }
                else this.value = val

                this.close()
            },
            remove (val = null) {
                if (val === null) {
                    this.value = this.multiple ? [] : null
                }
                else {
                    const index = this.value.indexOf(val)
                    this.value.splice(index, 1)
                }
            },
        }"
        x-on:click="open"
        x-on:click.away="close"
        class="relative">
        <div x-ref="anchor"
            x-bind:class="show && 'active'"
            class="form-input w-full">
            <div class="flex gap-3 {{ $empty ? 'form-input-caret' : '' }}">
                <div class="grow flex items-center gap-3">
                    @if ($icon = $attributes->get('icon')) 
                        <x-icon :name="$icon" class="text-gray-400"/> 
                    @endif

                    @if ($empty)
                        <input type="text" class="transparent grow" placeholder="{{ $placeholder }}" readonly>
                    @else
                        <div class="grow">
                            @if ($slot->isNotEmpty())
                                {{ $slot }}
                            @elseif ($multiple)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($value as $val)
                                        <div class="bg-slate-200 rounded-md px-2 border border-gray-200">
                                            <div class="flex items-center gap-2 max-w-[200px]">
                                                <div class="truncate text-xs font-medium">
                                                    {{ data_get($parsedOptions->firstWhere('value', $val), 'label') }}
                                                </div>
                                                <div class="shrink-0 text-sm flex items-center justify-center">
                                                    <x-close x-on:click.stop="remove({{ json_encode($val) }})"/>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                {{ data_get($parsedOptions->firstWhere('value', $value), 'label') }}
                            @endif
                        </div>
                    @endif
                </div>

                @if (!$empty)
                    <div class="shrink-0">
                        <x-close x-on:click.stop="remove()"/>
                    </div>
                @endif
            </div>
        </div>

        <div x-ref="dd"
            x-show="show"
            x-transition.opacity
            class="absolute z-20 bg-white shadow-lg rounded-lg border border-gray-300 overflow-hidden w-full mt-px min-w-[300px]">
            @if ($searchable)
                <div wire:ignore class="p-3 border-b">
                    <div
                        x-data="{ focus: false }"
                        x-bind:class="focus && 'active'"
                        class="form-input flex items-center gap-3 w-full">
                        <div class="shrink-0 text-gray-400">
                            <x-icon name="search"/>
                        </div>

                        <input type="text" id="select-input-search"
                            x-on:focus="focus = true"
                            x-on:blur="focus = false"
                            wire:model.debounce.300ms="selectInputSearchText"
                            class="transparent grow" 
                            placeholder="{{ __('Search') }}">

                        <div x-show="!empty($wire.get('selectInputSearchText'))" class="shrink-0">
                            <x-close wire:click="$set('selectInputSearchText', null)"/>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col divide-y">
                <div class="max-h-[250px] overflow-auto flex flex-col divide-y">
                    @isset($options)
                        {{ $options }}
                    @else
                        @forelse ($parsedOptions->filter(function($opt) {
                            $search = str()->lower($this->selectInputSearchText);
                            return str(data_get($opt, 'label'))->lower()->is("*{$search}*")
                                || str(data_get($opt, 'small'))->lower()->is("*{$search}*")
                                || str(data_get($opt, 'remark'))->lower()->is("*{$search}*");
                        })->values() as $opt)
                            @if (data_get($opt, 'is_group'))
                                <div wire:key="{{ uniqid() }}" 
                                    class="py-2 px-4 flex items-center gap-3 font-semibold bg-gray-100">
                                    @if ($icon = data_get($opt, 'icon'))
                                        <x-icon :name="$icon" class="shrink-0 text-gray-500"/>
                                    @endif
                                    <div class="grow font-semibold">{{ data_get($opt, 'label') }}</div>
                                    <x-icon name="chevron-down" class="shrink-0" size="12"/>
                                </div>
                            @else
                                <div wire:key="{{ uniqid() }}" 
                                    x-on:click.stop="select(@js(data_get($opt, 'value')))"
                                    class="py-2 px-4 flex items-center gap-3 cursor-pointer {{ data_get($opt, 'color') }}">
                                    @if ($avatar = data_get($opt, 'avatar'))
                                        <div class="shrink-0">
                                            <x-image avatar size="32x32" color="random"
                                                :src="is_string($avatar) ? $avatar : data_get($avatar, 'url')"
                                                :placeholder="data_get($avatar, 'placeholder')"/>
                                        </div>
                                    @elseif ($image = data_get($opt, 'image'))
                                        <div class="shrink-0">
                                            <x-image size="32x32"
                                                :src="is_string($image) ? $image : data_get($image, 'url')"
                                                :placeholder="data_get($image, 'placeholder')"/>
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
                                            @if (!empty($remark))
                                                <div class="text-sm font-medium text-gray-500">{{ $remark }}</div>
                                            @endif
                                            
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
                                </div>
                            @endif
                        @empty
                            <div wire:key="{{ uniqid() }}">
                                <x-no-result xs
                                    title="atom::common.empty.option.title"
                                    subtitle="atom::common.empty.option.subtitle"/>
                            </div>
                        @endforelse
                    @endisset
                </div>

                @isset($foot)
                    @if ($foot->isNotEmpty())
                        {{ $foot }}
                    @else
                        <a class="py-2 px-4 flex items-center justify-center gap-2" {{ $foot->attributes->except('label', 'icon') }}>
                            @if ($icon = $foot->attributes->get('icon')) <x-icon :name="$icon"/> @endif
                            {{ __($foot->attributes->get('label', '')) }}
                        </a>
                    @endif
                @endisset
            </div>
        </div>
    </div>
</x-form.field>