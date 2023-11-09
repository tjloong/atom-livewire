@php
    $time = $attributes->get('time', false);
    $range = $attributes->get('range', false);
    $placeholder = $attributes->get('placeholder', collect([
        'common.label.select-date' => !$time && !$range,
        'common.label.select-time' => $time && !$range,
        'common.label.select-date-range' => $range,
    ])->filter()->keys()->first());
@endphp

<x-form.field {{ $attributes }}>
@if ($range)
    <div wire:ignore x-cloak
        x-data="{
            range: @entangle($attributes->wire('model')),
            show: false,
            get from () { return this.range ? this.range.split(' to ')[0] : null },
            get to () { return this.range ? this.range.split(' to ')[1] : null },
            preset (opt) {
                let from = {
                    today: dayjs(),
                    yesterday: dayjs().subtract(1, 'day'),
                    'this-month': dayjs().startOf('month'),
                    'this-year': dayjs().startOf('year'),
                    'last-7-days': dayjs().subtract(6, 'day'),
                    'last-30-days': dayjs().subtract(29, 'day'),
                    'last-month': dayjs(`${dayjs().get('year')}-${dayjs().get('month')}-01`, 'YYYY-MM-DD'),
                    'last-year': dayjs(`${dayjs().get('year')-1}-01-01`, 'YYYY-MM-DD'),
                }[opt]

                let to = {
                    today: dayjs(),
                    yesterday: dayjs().subtract(1, 'day'),
                    'this-month': dayjs().endOf('month'),
                    'this-year': dayjs().endOf('year'),
                    'last-7-days': dayjs(),
                    'last-30-days': dayjs(),
                    'last-month': from.endOf('month'),
                    'last-year': from.endOf('year'),
                }[opt]

                from = from.format('YYYY-MM-DD')
                to = to.format('YYYY-MM-DD')
                this.range = `${from} to ${to}`
            }
        }"
        x-modelable="range"
        x-init="$watch('range', () => show = false)"
        x-on:click.away="show = false"
        x-bind:class="show && 'active'"
        {{ $attributes
            ->merge(['class' => 'form-input w-full relative'])
            ->except(['time', 'range', 'placeholder']) }}>
        <button type="button" class="flex items-center gap-3 w-full"
            x-on:click.stop="show = true">
            <div class="shrink-0 text-gray-400">
                <x-icon name="calendar"/>
            </div>
    
            <template x-if="from && to">
                <div class="grow flex items-center gap-3">
                    <div x-text="formatDate(from)" class="grow text-center truncate"></div>
                    <x-icon name="arrow-right" class="shrink-0"/>
                    <div x-text="formatDate(to)" class="grow text-center truncate"></div>
                </div>
            </template>

            <template x-if="!from || !to">
                <input type="text" placeholder="{{ tr($placeholder) }}" readonly
                    class="transparent grow cursor-pointer">
            </template>
    
            <div class="shrink-0">
                <x-icon name="dropdown-caret"/>
            </div>
        </button>

        <div 
            x-ref="dd"
            x-show="show"
            x-transition
            class="absolute left-0 top-full mt-px z-10 w-max bg-white rounded-md shadow-lg border">
            <div class="flex flex-col divide-y">
                @foreach ([
                    'today',
                    'yesterday',
                    'this-month',
                    'this-year',
                    'last-7-days',
                    'last-30-days',
                    'last-month',
                    'last-year',
                ] as $opt)
                    <div 
                        x-on:click="preset(@js($opt))"
                        class="py-2 px-4 hover:bg-slate-50 cursor-pointer">
                        {{ tr('common.label.'.$opt) }}
                    </div>
                @endforeach

                <div class="py-2">
                    <x-form.date.picker x-model="range" :config="['mode' => 'range']"/>
                </div>
            </div>
        </div>
    </div>
@elseif ($time)
@else
    <div wire:ignore x-cloak
        x-data="{
            date: @entangle($attributes->wire('model')),
            show: false,
        }"
        x-modelable="date"
        x-on:click.away="show = false"
        x-bind:class="show && 'active'"
        {{ $attributes->merge(['class' => 'form-input w-full relative']) }}>
        <button type="button" class="flex items-center gap-3 w-full"
            x-on:click.stop="show = true">
            <div class="shrink-0 text-gray-400">
                <x-icon name="calendar"/>
            </div>
    
            <input type="text" placeholder="{{ tr($placeholder) }}" readonly
                x-bind:value="formatDate(date)"
                class="transparent grow cursor-pointer">
    
            <div class="shrink-0">
                <x-icon name="dropdown-caret"/>
            </div>
        </button>

        <div 
            x-ref="dd"
            x-show="show"
            x-on:input.stop="show = false"
            x-transition
            class="absolute left-0 top-full mt-px z-10 w-max bg-white rounded-md shadow-lg border">
            <x-form.date.picker x-model="date"/>
        </div>
    </div>
@endif

    {{-- <div wire:ignore 
        x-cloak
        x-data="{
            value: null,
            show: false,
            focus: false,
            inputs: null,
            datepicker: null,
            config: {
                time: @js($time),
                format: @js($format),
            },
            clear () {
                this.setInputs(false)
                this.setValue()
            },
            setInputs (val) {
                const datetimeobject = val ? dayjs(val, 'YYYY-MM-DD HH:mm:ss') : null

                this.inputs = { 
                    date: datetimeobject?.format('YYYY-MM-DD') || null,
                    datetimeobject,
                }

                if (this.config.time) {
                    this.inputs = {
                        ...this.inputs,
                        hour: datetimeobject ? (
                            this.config.format === 12
                                ? datetimeobject.format('h')
                                : datetimeobject.format('H')
                        ) : null,
                        minute: datetimeobject?.get('minute').toString() || null,
                        second: datetimeobject?.get('second').toString() || null,
                        am: datetimeobject?.format('A') || null,
                    }
                }
            },
            setValue () {
                if (this.inputs.date) {
                    let datetimeobject = this.inputs.datetimeobject

                    if (this.config.time) {
                        const hour = this.config.format === 12 && this.inputs.am === 'PM'
                            ? +this.inputs.hour + 12
                            : this.inputs.hour

                        datetimeobject = datetimeobject
                            .set('hour', hour)
                            .set('minute', this.inputs.minute)
                            .set('second', this.inputs.second)

                        this.value = datetimeobject.utc().toISOString()
                    }
                    else this.value = datetimeobject.format('YYYY-MM-DD')
                }
                else this.value = null
            },
            setDatePicker () {
                this.$nextTick(() => {
                    if (this.show) {
                        floatDropdown(this.$refs.anchor, this.$refs.dd)

                        this.datepicker = flatpickr(this.$refs.datepicker, {
                            inline: true,
                            dateFormat: 'Y-m-d',
                            defaultDate: this.inputs.date,
                            onClose: () => this.show = false,
                            onChange: (selectedDate, dateStr) => {
                                this.setInputs(dateStr)
                                this.setValue()
                            },
                        })
                    }
                    else {
                        this.datepicker.destroy()
                        this.datepicker = null
                    }
                })
            },
        }"
        x-init="() => {
            if (@js($wire)) setInputs($wire.get(@js($wire)))
            else setInputs(value)
            $watch('show', () => setDatePicker())
            $watch('value', () => setInputs(value))
        }"
        x-modelable="value"
        x-on:click="focus = true"
        x-on:click.away="focus = false"
        class="flex items-center flex-wrap gap-2 w-full"
        {{ $attributes->except(['format', 'time']) }}>
        <div class="relative w-full">
            <div x-ref="anchor"
                x-on:click="show = true"
                x-on:click.away="show = false"
                x-bind:class="{
                    'active': focus,
                    'select': !value,
                }"
                class="flex items-center gap-2 form-input w-full">
                <x-icon name="calendar" class="shrink-0 text-gray-400"/>
                <input type="text" placeholder="{{ __('Select Date') }}" readonly
                    x-bind:value="inputs.date ? formatDate(inputs.date) : null"
                    class="transparent grow">
                <x-close x-show="inputs.date" x-on:click="clear" class="shrink-0"/>
            </div>

            <div x-ref="dd" x-show="show" x-transition x-on:click.stop class="absolute z-20">
                <div x-ref="datepicker"></div>
            </div>
        </div>

        <div 
            x-show="config.time"
            x-bind:class="{ 'active': focus }"
            class="flex items-center gap-2 form-input w-full">
            <x-icon name="clock" class="shrink-0 text-gray-400"/>

            <select x-model="inputs.hour" x-on:input.stop x-on:change="setValue" class="grow">
                <option selected>--</option>
                @foreach ($format === 24 ? range(0, 23) : range(1, 12) as $n)
                    <option value="{{ $n }}">{{ str()->padLeft($n, 2, '0') }}</option>
                @endforeach
            </select>

            <span class="font-bold">:</span>

            <select x-model="inputs.minute" x-on:input.stop x-on:change="setValue" class="grow">
                <option selected>--</option>
                @foreach (range(0, 59) as $n)
                    <option value="{{ $n }}">{{ str()->padLeft($n, 2, '0') }}</option>
                @endforeach
            </select>

            <span class="font-bold">:</span>

            @if ($format == 12)
                <select x-model="inputs.am" x-on:input.stop x-on:change="setValue" class="grow">
                    <option selected>--</option>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
            @else
                <select x-model="inputs.second" x-on:input.stop x-on:change="setValue" class="grow">
                    <option selected>--</option>
                    @foreach (range(0, 59) as $n)
                        <option value="{{ $n }}">{{ str()->padLeft($n, 2, '0') }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div> --}}
</x-form.field>