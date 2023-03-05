@props([
    'prefix' => $prefix ?? $attributes->get('prefix'),
    'postfix' => $postfix ?? $attributes->get('postfix'),
    'uid' => make_component_uid([
        $attributes->wire('model')->value(),
        $attributes->get('label'),
        'amount-input',
    ]),
])

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            focus: false,
            value: @js($attributes->get('value')),
            wire: @js($attributes->wire('model')->value()),
            get formatted () {
                if (!this.value) return null

                const split = `${this.value}`.split('.')

                if (split.length === 2 && split[1].length > 2) {
                    split[1] = split[1].substring(0, 2)
                    return split.join('.')
                }

                return this.value
            },
            init () {
                if (this.wire) this.value = this.$wire.get(this.wire)

                this.$watch('formatted', (val) => {
                    this.$dispatch(@js($uid.'-updated'), val)
                })
            },
            setFocus (bool) {
                this.focus = bool
            },
        }"
        x-bind:class="focus && 'active'"
        {{ $attributes
            ->merge(['id' => $uid])
            ->class([
                'form-input w-full flex items-center gap-2',
                $attributes->get('class'),
                !empty($attributes->get('error')) ? 'error' : null,
            ])
            ->only(['id', 'class']) }}
    >
        @if (is_string($prefix))
            @if (str($prefix)->is('icon:*')) <x-icon :name="str($prefix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($prefix) }}</div>
            @endif
        @else {{ $prefix }}
        @endif

        {{-- must use number input to have numpad in mobile --}}
        <div class="grow">
            <input type="number"
                x-bind:value="formatted"
                x-on:focus="setFocus(true)"
                x-on:blur="setFocus(false)"
                x-on:input="value = $event.target.value"
                step=".01"
                class="w-full"
                {{ $attributes
                    ->filter(fn($val, $key) => !str($key)->is('wire:*'))
                    ->except(['error', 'required', 'caption', 'label', 'id', 'class']) }}
            >

            <div
                x-ref="input"
                x-on:{{ $uid }}-updated.window="$dispatch('input', $event.detail)"
                class="hidden"
                {{ $attributes->whereStartsWith('wire:') }}
            ></div>
        </div>

        @if (is_string($postfix))
            @if (str($postfix)->is('icon:*')) <x-icon :name="str($postfix)->replace('icon:', '')->toString()" class="text-gray-400"/>
            @else <div class="shrink-0 text-gray-500 font-medium">{{ __($postfix) }}</div>
            @endif
        @else {{ $postfix }}
        @endif
    </div>
</x-form.field>
