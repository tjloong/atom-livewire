@props([
    'placeholder' => $attributes->get('placeholder', 'Select Date Range'),
])

<x-form.field {{ $attributes }}>
    <div
        x-data="{
            value: @entangle($attributes->wire('model')),
            focus: false,
            calendar: null,
            get from () {
                return this.value 
                    ? this.value.split(' to ')[0]
                    : null
            },
            get to () {
                return this.value
                    ? this.value.split(' to ')[1]
                    : null
            },
            open () {
                this.focus = true
                this.$nextTick(() => {
                    floatDropdown(this.$refs.anchor, this.$refs.dd)
                    this.setCalendar()
                })
            },
            close () {
                this.focus = false
                this.calendar?.destroy()
                this.calendar = null
            },
            setCalendar () {
                if (!this.calendar) {
                    this.calendar = flatpickr(this.$refs.calendar, {
                        mode: 'range',
                        inline: true,
                        dateFormat: 'Y-m-d',
                        onChange: (selectedDate, dateStr) => {
                            [from, to] = selectedDate
                            
                            from = from ? dayjs(from).format('YYYY-MM-DD') : null
                            to = to ? dayjs(to).format('YYYY-MM-DD') : null

                            if (from && to) {
                                this.value = `${from} to ${to}`
                                this.close()
                            }
                        },
                    })
                }

                this.calendar.setDate(this.value)
            },
        }"
        x-on:click.away="close"
        x-on:input.stop
        x-bind:class="focus && 'active'"
        class="form-input cursor-pointer w-full">
        <div x-ref="anchor" x-on:click="open">
            <div class="flex items-center gap-3">
                <div class="shrink-0 text-gray-400">
                    <x-icon name="calendar"/>
                </div>

                <div x-show="from && to" class="grow flex items-center gap-3">
                    <div x-text="formatDate(from)"></div>
                    <x-icon name="arrow-right"/>
                    <div x-text="formatDate(to)"></div>
                    <x-close x-on:click.stop="value = null"/>
                </div>

                <div x-show="!from || !to" class="grow flex items-center gap-3">
                    <div class="grow text-gray-400">{{ __($placeholder) }}</div>
                    <x-icon name="chevron-down"/>
                </div>

            </div>
        </div>

        <div
            x-ref="dd"
            x-show="focus"
            x-transition.opacity
            class="absolute z-20 bg-white border border-gray-300 shadow-lg rounded-md">
            <div x-ref="calendar"></div>
        </div>
    </div>
</x-form.field>