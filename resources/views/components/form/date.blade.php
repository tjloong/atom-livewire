<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <div
        x-data="{
            show: false,
            value: @js($attributes->get('value')) || @entangle($attributes->wire('model')),
            settings: @js($attributes->get('settings')),
            calendar: null,
            placeholder: @js($attributes->get('placeholder', 'Select Date')),
            open () {
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
            x-bind:class="show && 'active'"
            class="flex items-center gap-2 form-input w-full {{ !empty($attributes->get('error')) ? 'error' : '' }}"
        >
            <x-icon name="calendar" size="18px" class="text-gray-400"/>

            <div 
                x-on:click="open()" 
                x-text="value ? formatDate(value) : placeholder" 
                x-bind:class="!value && 'text-gray-400'"
                class="grow cursor-pointer">
            </div>

            <a x-show="value" x-on:click="clear()" class="px-2 flex">
                <x-icon name="xmark" size="15px" class="m-auto"/>
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

