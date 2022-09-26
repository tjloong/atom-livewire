<x-form.field {{ $attributes->only(['error', 'required', 'caption', 'label']) }}>
    <div
        x-data="{
            show: false,
            value: @js($attributes->get('value')) || @entangle($attributes->wire('model')),
            settings: @js($attributes->get('settings')),
            calendar: null,
            placeholder: @js(__($attributes->get('placeholder', 'Select '.$attributes->get('label', 'Date')))),
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
                this.value = null
                this.close()
            },
            setCalendar () {
                if (!this.calendar) {
                    this.calendar = flatpickr(this.$refs.datepicker, {
                        inline: true,
                        dateFormat: 'Y-m-d',
                        onClose: () => this.close(),
                        onChange: (selectedDate, dateStr) => this.value = dateStr,
                        ...this.settings,
                    })
                }

                this.calendar.setDate(this.value)
            },
        }"
        x-on:click.away="close()"
        class="relative"
    >
        <div
            x-ref="anchor" 
            x-bind:class="{
                'active': show,
                'select': !value,
            }"
            {{ $attributes->class([
                'flex items-center gap-2 form-input w-full',
                'error' => !empty($attributes->get('error')),
            ]) }}
        >
            <x-icon name="calendar" class="text-gray-400"/>

            <div 
                x-on:click="open()" 
                x-text="value ? formatDate(value) : placeholder" 
                x-bind:class="!value && 'text-gray-400'"
                class="grow cursor-pointer">
            </div>

            <a x-show="value" x-on:click="clear()" class="flex text-gray-500">
                <x-icon name="xmark" class="m-auto"/>
            </a>
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

