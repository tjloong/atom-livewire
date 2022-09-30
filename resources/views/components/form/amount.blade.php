@props([
    'prefix' => $prefix ?? $attributes->get('prefix'),
    'postfix' => $postfix ?? $attributes->get('postfix'),
    'uid' => make_component_uid([
        $attributes->wire('model')->value(),
        $attributes->get('label'),
        'amount-input',
    ]),
])

<x-form.field {{ $attributes->only(['error', 'required', 'caption', 'label']) }}>
    <div 
        x-data="{
            wire: @js(!empty($attributes->wire('model')->value())),
            value: @js($attributes->get('value')),
            entangle: @entangle($attributes->wire('model')),
            focus: false,
            init () {
                if (this.wire) {
                    this.value = this.entangle
                    this.$watch('entangle', (val) => this.value = val)
                }
            },
            onFocus () {
                this.focus = true
                const end = this.$refs.input.value.length
                this.$nextTick(() => this.$refs.input.setSelectionRange(end, end))
            },
            input (e) {
                let num = e.target.value.replace(/\D/g, '').trim()
                if (num.length < 3) num = num.padStart(3, '0')

                const head = num.slice(0, num.length - 2)
                const tail = num.slice(-2)

                this.value = [(+head).toString(), tail].join('.')

                if (this.wire) this.entangle = this.value
                else this.$dispatch('input', this.value)
            },

        }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2 {{ !empty($attributes->get('error')) ? 'error' : '' }}"
        id="{{ $uid }}"
    >
        @if (is_string($prefix))
            @if (str($prefix)->is('icon:*')) <x-icon :name="str($prefix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($prefix) }}</div>
            @endif
        @else {{ $prefix }}
        @endif

        <input type="text"
            x-ref="input"
            x-model="value"
            x-on:focus="onFocus"
            x-on:blur="focus = false"
            x-on:input="input"
            {{ $attributes
                ->class(['form-input transparent w-full'])
                ->merge([
                    'step' => '.01',
                    'placeholder' => __($attributes->get('placeholder')),
                ])
                ->only(['class', 'step', 'placeholder']) 
            }}
        >

        @if (is_string($postfix))
            @if (str($postfix)->is('icon:*')) <x-icon :name="str($postfix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($postfix) }}</div>
            @endif
        @else {{ $postfix }}
        @endif
    </div>
</x-form.field>
