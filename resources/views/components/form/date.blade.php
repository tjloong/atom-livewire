@props([
    'placeholder' => __(
        $attributes->get('placeholder') 
        ?? 'Select '.component_label($attributes, 'Date')
    ),
])

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            show: false,
            value: null,
            calendar: null,
            settings: @js($attributes->get('settings')),
            placeholder: @js($placeholder),
            open () {
                if (this.show) return this.close()
                this.show = true
                this.$nextTick(() => {
                    floatDropdown(this.$refs.anchor, this.$refs.dd)
                    this.setCalendar()
                })
            },
            close () {
                this.show = false
                this.$nextTick(() => {
                    if (!this.calendar) return
                    this.calendar.destroy()
                    this.calendar = null
                })
            },
            clear () {
                this.input()
                this.close()
            },
            input (val = null) {
                this.value = val
                this.$refs.input.value = val
                this.$refs.input.dispatchEvent(new Event('input', { bubble: true }))
            },
            setCalendar () {
                if (!this.calendar) {
                    this.calendar = flatpickr(this.$refs.datepicker, {
                        inline: true,
                        dateFormat: 'Y-m-d',
                        onClose: () => this.close(),
                        onChange: (selectedDate, dateStr) => this.input(dateStr),
                        ...this.settings,
                    })
                }

                this.calendar.setDate(this.value)
            },
        }"
        x-init="value = $refs.input.value.trim() || null"
        x-on:click.away="close()"
        class="relative"
    >
        <input x-ref="input"
            type="text" 
            class="hidden"
            {{ $attributes->except(['error', 'required', 'caption', 'label'])}}
        >

        <div
            x-ref="anchor" 
            x-bind:class="{
                'active': show,
                'select': !value,
            }"
            {{ $attributes->class([
                'flex items-center gap-2 form-input w-full',
                'error' => component_error(optional($errors), $attributes),
            ]) }}
        >
            <x-icon name="calendar" class="text-gray-400"/>

            <div 
                x-on:click="open()" 
                x-text="value ? formatDate(value) : placeholder" 
                x-bind:class="!value && 'text-gray-400'"
                class="grow cursor-pointer">
            </div>

            <x-close x-show="value" x-on:click="clear()"/>
        </div>

        <div
            x-ref="dd"
            x-show="show"
            x-transition.opacity
            class="absolute z-20"
        >
            <div x-ref="datepicker"></div>
        </div>
    </div>
</x-form.field>

