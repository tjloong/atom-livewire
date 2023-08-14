@props([
    'placeholder' => $attributes->get('placeholder')
        ? __($attributes->get('placeholder'))
        : __('Select').' '.component_label($attributes, 'Date'),
    'config' => $attributes->get('config'),
])

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            focus: false,
            picker: null,
            value: @entangle($attributes->wire('model')),
            config: @js($config),
            get placeholder () {
                if (this.value) return formatDate(this.value)
                else return @js($placeholder)
            },
            setFocus (bool) {
                this.focus = bool

                if (bool) {
                    this.$nextTick(() => {
                        floatDropdown(this.$refs.anchor, this.$refs.dd)
                        this.setPicker()
                    })
                }
                else if (this.picker) {
                    this.$nextTick(() => {
                        this.picker.destroy()
                        this.picker = null
                    })
                }
            },
            clear () {
                this.value = null
                this.setFocus(false)
            },
            setPicker () {
                if (!this.picker) {
                    this.picker = flatpickr(this.$refs.datepicker, {
                        inline: true,
                        dateFormat: 'Y-m-d',
                        onClose: () => this.setFocus(false),
                        onChange: (selectedDate, dateStr) => this.value = dateStr,
                        ...this.config,
                    })
                }

                this.picker.setDate(this.value)
            },
        }"
        x-on:click.away="setFocus(false)"
        class="relative cursor-pointer"
        {{ $attributes->class(['relative', 'cursor-pointer'])->except(['placeholder', 'config']) }}
    >
        <div
            x-ref="anchor" 
            x-on:click="setFocus(true)"
            x-bind:class="{
                'active': focus,
                'select': !value,
            }"
            class="flex items-center gap-2 form-input w-full {{
                component_error(optional($errors), $attributes) ? 'error' : null
            }}"
        >
            <x-icon name="calendar" class="shrink-0 text-gray-400"/>
            <div x-on:click="setFocus(true)" 
                x-text="placeholder" 
                x-bind:class="value ? 'grow' : 'grow text-gray-400'"></div>
            <x-close x-show="value" x-on:click="clear()" class="shrink-0"/>
        </div>

        <div x-ref="dd" x-show="focus" x-transition.opacity class="absolute z-20">
            <div x-ref="datepicker"></div>
        </div>
    </div>
</x-form.field>

