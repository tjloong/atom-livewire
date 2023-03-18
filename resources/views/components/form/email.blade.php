@props([
    'prefix' => $prefix ?? $attributes->get('prefix'),
    'postfix' => $postfix ?? $attributes->get('postfix'),
    'placeholder' => __($attributes->get('placeholder')),
    'multiple' => $attributes->get('multiple', false),
    'options' => $attributes->get('options', []),
])

<x-form.field {{ $attributes }}>
    @if ($multiple || $options)
        <div
            x-data="{
                text: null,
                focus: false,
                value: @entangle($attributes->wire('model')),
                multiple: @js($multiple),
                get options () {
                    return @js($options).filter(opt => (
                        !this.text 
                        || (typeof opt === 'string' && opt.includes(this.text))
                        || (typeof opt === 'object' && (opt.name.includes(this.text) || opt.email.includes(this.text)))
                    ))
                },
                setFocus (bool = true) {
                    this.focus = bool
                    if (bool) this.$refs.input.focus()
                    else this.text = null
                },
                pick (opt = null) {
                    if (!opt && this.options.length) opt = this.options[0]

                    const val = opt?.email || opt || this.text

                    if (this.multiple) {
                        if (!this.value) this.value = []
                        if (!empty(val) && !this.value.includes(val)) this.value.push(val)
                    }
                    else this.value = val

                    this.setFocus(false)
                },
                remove (val) {
                    const index = this.value.indexOf(val)
                    this.value.splice(index, 1)
                    this.setFocus(false)
                },
            }"
            x-on:click.away="setFocus(false)"
            class="relative"
        >
            <div x-ref="anchor" x-on:click="setFocus">
                @if ($multiple)
                    <div
                        x-bind:class="focus && 'active'"
                        {{ $attributes->class([
                            'form-input w-full flex flex-wrap items-center gap-2',
                            'error' => component_error(optional($errors), $attributes),
                        ])->except(['multiple', 'options', 'placeholder']) }}
                    >
                        <template x-for="(val, i) in (value || [])" x-bind:key="`${val}-${i}`">
                            <div 
                                x-bind:class="
                                    /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val)
                                        ? 'bg-gray-200 border-gray-300 text-black'
                                        : 'bg-red-100 border-red-300 text-red-500'
                                "
                                class="shrink-0 rounded-lg overflow-hidden flex items-center gap-2 border"
                            >
                                <div x-text="val" class="pl-2 text-sm" ></div>
                                <div x-on:click.stop="remove(val)" class="p-1 flex cursor-pointer">
                                    <x-icon name="close" class="m-auto text-gray-500" size="14"/>
                                </div>
                            </div>
                        </template>

                        <input type="email"
                            x-ref="input"
                            x-model="text"
                            x-on:keydown.enter.prevent="pick()"
                            x-on:keydown.space.prevent="pick()"
                            x-on:input.stop
                            placeholder="{{ $placeholder }}"
                            class="grow"
                        >
                    </div>
                @else
                    <input type="email" 
                        x-model="text" 
                        placeholder="{{ $placeholder }}" 
                        {{ $attributes->class([
                            'form-input w-full',
                            'error' => component_error(optional($errors), $attributes),
                        ])->except(['multiple', 'options', 'placeholder']) }}
                    >
                @endif
            </div>

            <div
                x-ref="dd"
                x-show="(focus && options.length) || text"
                x-transition.opacity
                class="absolute z-20 w-full mt-px bg-white border border-gray-300 shadow-lg rounded-md max-w-md min-w-[250px] overflow-hidden"
            >
                <div class="flex flex-col divide-y">
                    <template x-for="(opt, i) in options" x-bind:key="`${opt}-${i}`">
                        <div 
                            x-on:click.stop="pick(opt)" 
                            class="py-2 px-4 text-gray-800 cursor-pointer hover:bg-slate-100"
                        >
                            <template x-if="typeof opt === 'string'">
                                <div class="font-medium" x-text="opt"></div>
                            </template>

                            <template x-if="typeof opt === 'object'">
                                <span class="font-medium" x-text="opt.name"></span>
                                <span x-text="'<'+opt.email+'>'"></span>
                            </template>
                        </div>
                    </template>

                    <template x-if="multiple && !options.length && text">
                        <div class="p-1">
                            <div class="flex items-center justify-center gap-2 text-sm font-medium py-2 px-4 bg-gray-100">
                                <x-icon name="circle-info" class="text-gray-400" size="12"/>
                                <div class="text-gray-500">{{ __('Press spacebar to add email') }}</div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    @else
        <div 
            x-data="{ focus: false }"
            x-bind:class="focus && 'active'"
            class="form-input w-full flex items-center gap-2 {{ 
                component_error(optional($errors), $attributes) ? 'error' : ''
            }}"
        >
            @if (is_string($prefix))
                @if (str($prefix)->is('icon:*')) <x-icon :name="str($prefix)->replace('icon:', '')->toString()" class="text-gray-400"/>
                @else <div class="shrink-0 text-gray-500 font-medium">{{ __($prefix) }}</div>
                @endif
            @else {{ $prefix }}
            @endif

            <div class="grow">
                <input type="email" class="w-full"
                    placeholder="{{ $placeholder }}" 
                    x-on:focus="focus = true"
                    x-on:blur="focus = false"
                    {{ $attributes
                        ->class(['form-input transparent w-full'])
                        ->merge(['placeholder' => $placeholder])
                        ->except(['error', 'caption', 'label', 'tag', 'prefix', 'postfix'])
                    }}
                >
            </div>

            @if ($attributes->get('clear', false))
                @if ($model = $attributes->wire('model')->value()) <x-close wire:click="$set('{{ $model }}', null)" class="-m-1"/>
                @else <x-close x-on:click="$dispatch('clear')" class="-m-1"/>
                @endif
            @else
                @if (is_string($postfix))
                    @if (str($postfix)->is('icon:*')) <x-icon :name="str($postfix)->replace('icon:', '')->toString()" class="text-gray-400"/>
                    @else <div class="shrink-0 text-gray-500 font-medium">{{ __($postfix) }}</div>
                    @endif
                @elseif ($postfix)
                    {{ $postfix }}
                @endif

                @isset($button)
                    @php $label = $button->attributes->get('label') @endphp 
                    @php $icon = $button->attributes->get('icon') @endphp 
                    <a {{ $button->attributes->class([
                        'flex items-center justify-center gap-1 rounded-full -mr-1 text-sm',
                        $label ? 'px-2 py-0.5' : null,
                        !$label && $icon ? 'p-1' : null,
                        $button->attributes->get('class', 'text-gray-800 bg-gray-200'),
                    ]) }}">
                        @if ($icon) <x-icon :name="$icon" size="12"/> @endif
                        {{ __($label) }}
                    </a>
                @endisset
            @endif
        </div>
    @endif
</x-form.field>
