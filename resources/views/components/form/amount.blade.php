@props([
    'uid' => implode('-', array_filter([
        str()->slug(
            $attributes->wire('model')->value()
            ?? $attributes->get('label')
        ),
        'amount-input',
    ])),
    'prefix' => $prefix ?? $attributes->get('prefix'),
    'postfix' => $postfix ?? $attributes->get('postfix'),
    'wire' => $attributes->wire('model'),
])

<x-form.field {{ $attributes->only(['error', 'required', 'caption', 'label']) }}>
    <div 
        x-data 
        x-on:{{ $uid }}.window="$dispatch('input', $event.detail)" 
        {{ $wire }}
    ></div>

    <div 
        x-data="{
            uid: @js($uid),
            wire: @js($wire),
            value: @js($attributes->get('value')),
            focus: false,
            init () {
                if (this.wire) this.value = currency(parseFloat(this.$wire.get(this.wire.value)) || 0)
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
                this.$dispatch(this.uid, this.value)
            },

        }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2 {{ !empty($attributes->get('error')) ? 'error' : '' }}"
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
