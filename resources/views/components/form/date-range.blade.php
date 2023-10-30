@php
    $clearable = $attributes->get('clearable', true);
    $placeholder = $attributes->get('placeholder', 'common.label.select-date-range');
    $options = [
        'today',
        'yesterday',
        'this-month',
        'this-year',
        'last-7-days',
        'last-30-days',
        'last-month',
        'last-year',
    ];
@endphp

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
            init () {
                this.$watch('value', value => {
                    if (value === this.value) return
                    this.value = value
                    this.close()
                })
            },
            open () {
                this.focus = true
                this.$nextTick(() => {
                    floatPositioning(this.$refs.anchor, this.$refs.dd)
                    this.setCalendar()
                })
            },
            close () {
                this.focus = false
                this.calendar?.destroy()
                this.calendar = null
            },
            preset (val) {
                const from = {
                    today: dayjs(),
                    yesterday: dayjs().subtract(1, 'day'),
                    'this-month': dayjs().startOf('month'),
                    'this-year': dayjs().startOf('year'),
                    'last-7-days': dayjs().subtract(6, 'day'),
                    'last-30-days': dayjs().subtract(29, 'day'),
                    'last-month': dayjs(`${dayjs().get('year')}-${dayjs().get('month')}-01`, 'YYYY-MM-DD'),
                    'last-year': dayjs(`${dayjs().get('year')-1}-01-01`, 'YYYY-MM-DD'),
                }[val]

                const to = {
                    today: dayjs(),
                    yesterday: dayjs().subtract(1, 'day'),
                    'this-month': dayjs().endOf('month'),
                    'this-year': dayjs().endOf('year'),
                    'last-7-days': dayjs(),
                    'last-30-days': dayjs(),
                    'last-month': from.endOf('month'),
                    'last-year': from.endOf('year'),
                }[val]

                this.input(from.format('YYYY-MM-DD'), to.format('YYYY-MM-DD'))
            },
            input (from, to) {
                if (from && to) this.value = `${from} to ${to}`
                else this.value = null
                this.close()
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

                            if (from && to) this.input(from, to)
                        },
                    })
                }

                this.calendar.setDate(this.value)
            },
        }"
        x-on:click.away="close"
        x-on:input.stop
        class="relative w-full">
        <div x-ref="anchor" 
            x-on:click="open" 
            x-bind:class="focus && 'active'"
            class="form-input cursor-pointer">
            <div class="flex items-center gap-3">
                <div class="shrink-0 text-gray-400">
                    <x-icon name="calendar"/>
                </div>

                <div x-show="from && to" class="grow flex items-center gap-3">
                    <div x-text="formatDate(from)" class="grow text-center"></div>
                    <x-icon name="arrow-right" class="shrink-0"/>
                    <div x-text="formatDate(to)" class="grow text-center"></div>
                    @if ($clearable) <x-close x-on:click.stop="value = null" class="shrink-0"/> @endif
                </div>

                <div x-show="!from || !to" class="grow flex items-center gap-3">
                    <div class="grow text-gray-400">{{ tr($placeholder) }}</div>
                    <x-icon name="chevron-down"/>
                </div>
            </div>
        </div>

        <div
            x-ref="dd"
            x-show="focus"
            x-transition.opacity
            class="absolute z-20 bg-white border border-gray-300 shadow-lg rounded-md overflow-hidden w-max mt-1">
            <div class="flex flex-col divide-y">
                @foreach ($options as $opt)
                    <div 
                        x-on:click="preset(@js($opt))"
                        class="py-2 px-4 hover:bg-slate-50 cursor-pointer">
                        {{ tr('common.label.'.$opt) }}
                    </div>
                @endforeach

                <div class="py-2">
                    <label class="px-4">{{ tr('common.label.date-range') }}</label>
                    <div x-ref="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</x-form.field>