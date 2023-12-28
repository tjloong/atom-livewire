@php
    $prefix = $attributes->get('prefix');
    $suffix = $attributes->get('suffix');
    $icon = $attributes->get('icon', 'envelope');
    $multiple = $attributes->get('multiple', false);
    $placeholder = $attributes->get('placeholder');
    $options = $attributes->get('options', []);
    $except = ['prefix', 'suffix', 'icon', 'multiple', 'placeholder', 'options'];
@endphp

<x-form.field {{ $attributes }}>
    <div x-cloak wire:ignore
        x-data="{
            value: @entangle($attributes->wire('model')),
            text: null,
            focus: false,
            multiple: @js($multiple),
            get options () {
                return @js($options)
                    .map(opt => (typeof opt === 'string' ? { name: opt, email: opt } : opt))
                    .filter(opt => {
                        let search = this.text
                            ? (opt.email.includes(this.text) || opt.name.includes(this.text))
                            : true

                        let exists = this.multiple
                            ? this.value.some(val => (val.email === opt.email))
                            : this.value === opt.email

                        return !exists && search
                    })
            },
            select (opt) {
                if (this.multiple) {
                    if (!this.value) this.value = []
                    if (!this.value.some(val => (val.email === opt.email))) this.value.push(opt)
                    this.text = null
                }
                else this.text = this.value = opt.email

                this.$refs.input.focus()
            },
        }"
        x-modelable="value"
        x-init="() => {
            if (!multiple) {
                text = value
                $watch('text', () => value = text)
            }
        }"
        class="relative">
        <div class="flex items-center gap-2">
            <div
                x-init="$watch('focus', () => focus && $nextTick(() => $refs.input.focus()))"
                x-on:click="focus = true"
                x-on:click.away="focus = false"
                x-bind:class="focus && 'active'"
                class="form-input w-full flex gap-3 overflow-hidden">
                @if ($prefix)
                    <div class="shrink-0 text-gray-400 border-r border-gray-300 bg-gray-100 -ml-3 -my-1.5 py-1.5 px-2">
                        {!! $prefix !!}
                    </div>
                @endif

                @if ($icon)
                    <div class="shrink-0 text-gray-400"><x-icon :name="$icon"/></div>
                @endif

                <div class="grow flex items-center gap-2 flex-wrap">
                    <template x-for="(item, i) in (multiple ? (value || []) : [])">
                        <div
                            x-bind:class="
                                /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(item.email)
                                ? 'bg-gray-100 border-gray-300'
                                : 'bg-red-100 border-red-300 text-red-500'
                            "
                            class="text-sm rounded-md border flex items-center">
                            <div class="pl-2 grow font-medium" x-text="item.email"></div>
                            <div class="shrink-0 px-2 flex cursor-pointer" x-on:click="value.splice(i, 1)">
                                <x-icon name="xmark" class="m-auto"/>
                            </div>
                        </div>
                    </template>

                    <input x-ref="input" 
                        x-model="text"
                        x-on:keydown.enter.prevent="() => {
                            if (options.length) select(options[0])
                            else select({ name: text, email: text })
                        }"
                        type="email"
                        class="transparent grow"
                        placeholder="{!! tr($placeholder) !!}">
                </div>

                @if ($suffix)
                    <div class="shrink-0 text-gray-400 border-l border-gray-300 bg-gray-100 -mr-3 -my-1.5 py-1.5 px-2">
                        {!! $suffix !!}
                    </div>
                @endif
            </div>

            @isset($button) <x-button inverted {{ $button->attributes }}/> @endisset
        </div>

        <div x-ref="dropdown"
            x-show="focus && (
                (multiple && !empty(text))
                || (options && options.length > 0)
            )"
            x-transition
            class="absolute z-10 left-0 right-0 bg-white border rounded-md shadow-lg mt-px max-h-[250px] overflow-auto">
            <div class="flex flex-col">
                <div class="text-sm text-gray-500 font-medium border-b py-2 px-4 bg-slate-100">
                    {{ tr('app.label.press-enter-to-select') }}
                </div>

                <template x-for="opt in options" x-bind:key="opt.email">
                    <div x-on:click="select(opt)" class="py-2 px-4 cursor-pointer hover:bg-slate-50 border-b last:border-0">
                        <template x-if="opt.name === opt.email">
                            <div class="font-medium" x-text="opt.email"></div>
                        </template>

                        <template x-if="opt.name !== opt.email">
                            <div>
                                <div class="font-medium" x-text="opt.name"></div>
                                <div class="text-sm text-gray-500" x-text="opt.email"></div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-form.field>