@props([
    'format' => $attributes->get('format', 12),
    'time' => $attributes->get('time', true),
])

<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            value: @entangle($attributes->wire('model')),
            show: false,
            focus: false,
            inputs: null,
            datepicker: null,
            config: {
                time: @js($time),
                format: @js($format),
            },
            init () {
                this.setInputs(this.value)
                this.$watch('show', (val) => this.setDatePicker())
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
        x-on:click="focus = true"
        x-on:click.away="focus = false"
        wire:ignore
        wire:key="{{ $attributes->get('wire:key') }}"
        class="flex items-center flex-wrap gap-2 w-full"
    >
        <div class="relative w-full">
            <div x-ref="anchor"
                x-on:click="show = true"
                x-on:click.away="show = false"
                x-bind:class="{
                    'active': focus,
                    'select': !value,
                }"
                class="flex items-center gap-2 form-input w-full {{
                    component_error(optional($errors), $attributes) ? 'error' : null
                }}"
            >
                <x-icon name="calendar" class="shrink-0 text-gray-400"/>
                <input type="text" placeholder="{{ __('Select Date') }}" readonly
                    x-bind:value="inputs.date ? formatDate(inputs.date) : null"
                    class="form-input transparent grow">
                <x-close x-show="inputs.date" x-on:click="clear" class="shrink-0"/>
            </div>

            <div x-ref="dd" x-show="show" x-transition class="absolute z-20">
                <div x-ref="datepicker"></div>
            </div>
        </div>

        <div 
            x-show="config.time"
            x-bind:class="{ 'active': focus }"
            class="flex items-center gap-2 form-input w-full {{
                component_error(optional($errors), $attributes) ? 'error' : null
            }}"
        >
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
    </div>
</x-form.field>