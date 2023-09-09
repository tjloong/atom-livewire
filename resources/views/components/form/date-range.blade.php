@props([
    'getValue' => function($name = null) use ($attributes) {
        $value = $attributes->get('value') ?? data_get($this, $attributes->wire('model')->value());

        if ($name) {
            $split = explode(' to ', $value);

            if ($name === 'from') return $split[0];
            if ($name === 'to') return $split[1];
        }

        return $value;
    },
])

<x-form.field {{ $attributes }}>
    <div {{ $attributes->wire('model') }} x-on:date-range-input="$dispatch('input', $event.detail)">
        <div
            x-data="{
                value: @js($getValue()),
                focus: false,
                calendar: null,
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
                                    this.$dispatch('date-range-input', this.value)
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
            class="form-input cursor-pointer">
            <div x-ref="anchor" x-on:click="open">
                <div class="flex items-center gap-3">
                    <div class="shrink-0 text-gray-400">
                        <x-icon name="calendar"/>
                    </div>
                    <div>{{ format_date($getValue('from')) }}</div>
                    <x-icon name="arrow-right"/>
                    <div>{{ format_date($getValue('to')) }}</div>
                    <x-icon name="chevron-down"/>
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
    </div>
</x-form.field>