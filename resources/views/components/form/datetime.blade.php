@props([
    'format' => $attributes->get('format', 12),
])

<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            value: @entangle($attributes->wire('model')),
            format: @js($format),
            show: false,
            focus: false,
            inputs: null,
            datepicker: null,
            init () {
                this.setInputs()
                this.$watch('value', (val) => this.setInputs())
                this.$watch('show', (val) => this.setDatePicker())
            },
            getDayjs () {
                if (!this.value) return null
                return dayjs(this.value, 'YYYY-MM-DD HH:mm:ss')
            },
            setInputs () {
                const date = this.getDayjs()

                this.inputs = {
                    date: date ? date.format('YYYY-MM-DD') : null,
                    hour: date ? (
                        this.format === 12
                            ? date.format('h')
                            : date.format('H')
                    ) : null,
                    minute: date ? date.get('minute').toString() : null,
                    second: date ? date.get('second').toString() : null,
                    am: date ? date.format('A') : null,
                }
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
                            onChange: (selectedDate, dateStr) => this.setDate(dateStr),
                        })
                    }
                    else {
                        this.datepicker.destroy()
                        this.datepicker = null
                    }
                })
            },
            setDate (val) {
                let date = this.getDayjs()
                const newdate = dayjs(val, 'YYYY-MM-DD')
                
                if (date) {
                    date = date
                        .set('date', newdate.get('date'))
                        .set('month', newdate.get('month'))
                        .set('year', newdate.get('year'))

                    this.value = date.utc().format('YYYY-MM-DD HH:mm:ss')
                }
                else this.value = newdate.utc().format('YYYY-MM-DD HH:mm:ss')
            },
            setTime () {
                let date = this.getDayjs()

                const hour = this.format === 12 && this.inputs.am === 'PM'
                    ? +this.inputs.hour + 12
                    : this.inputs.hour

                date = date
                    .set('hour', hour)
                    .set('minute', this.inputs.minute)
                    .set('second', this.inputs.second)

                this.value = date.utc().format('YYYY-MM-DD HH:mm:ss')
            },
        }"
        x-on:click="focus = true"
        x-on:click.away="focus = false"
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
                <x-close x-show="inputs.date" x-on:click="value = null" class="shrink-0"/>
            </div>

            <div x-ref="dd" x-show="show" x-transition class="absolute z-20">
                <div x-ref="datepicker"></div>
            </div>
        </div>

        <div 
            x-bind:class="{
                'active': focus,
            }"
            class="flex items-center gap-2 form-input w-full {{
                component_error(optional($errors), $attributes) ? 'error' : null
            }}"
        >
            <x-icon name="clock" class="shrink-0 text-gray-400"/>

            <select x-model="inputs.hour" x-on:input.stop x-on:change="setTime" class="grow">
                <option selected>--</option>
                @foreach ($format === 24 ? range(0, 23) : range(1, 12) as $n)
                    <option value="{{ $n }}">{{ str()->padLeft($n, 2, '0') }}</option>
                @endforeach
            </select>

            <span class="font-bold">:</span>

            <select x-model="inputs.minute" x-on:input.stop x-on:change="setTime" class="grow">
                <option selected>--</option>
                @foreach (range(0, 59) as $n)
                    <option value="{{ $n }}">{{ str()->padLeft($n, 2, '0') }}</option>
                @endforeach
            </select>

            <span class="font-bold">:</span>

            @if ($format == 12)
                <select x-model="inputs.am" x-on:input.stop x-on:change="setTime" class="grow">
                    <option selected>--</option>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
            @else
                <select x-model="inputs.second" x-on:input.stop x-on:change="setTime" class="grow">
                    <option selected>--</option>
                    @foreach (range(0, 59) as $n)
                        <option value="{{ $n }}">{{ str()->padLeft($n, 2, '0') }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
</x-form.field>