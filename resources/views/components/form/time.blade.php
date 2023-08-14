@props([
    'format' => $attributes->get('format', 12),
])

<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            format: @js($format),
            value: @entangle($attributes->wire('model')), 
            focus: false,
            inputs: {
                hour: null,
                minute: null,
                second: null,
                am: null
            },
            init () {
                this.setInputs()
                this.$watch('inputs', (val) => this.setTime())
            },
            getTime (key = null) {
                if (!this.value) return null

                const date = dayjs(this.value, 'YYYY-MM-DD HH:mm:ss')

                if (!date.isValid()) return null
                else if (key === 'hour' && this.format === 12 && date.get('hour') > 12) return date.get('hour') - 12
                else if (key === 'hour') return date.get('hour') 
                else if (key === 'minute') return date.get('minute') 
                else if (key === 'second') return date.get('second') 
                else if (key === 'am') return date.format('A')
                else return date
            },
            setTime () {
                let time = this.getTime() || dayjs()

                const hour = this.format === 12 && this.inputs.am === 'PM'
                    ? +this.inputs.hour + 12
                    : this.inputs.hour

                time = time
                    .set('hour', hour)
                    .set('minute', this.inputs.minute)
                    .set('second', this.inputs.second)

                this.value = time.utc().format('HH:mm:ss')
            },
            setInputs () {
                this.inputs.hour = this.getTime('hour')
                this.inputs.minute = this.getTime('minute')
                this.inputs.second = this.getTime('second')
                this.inputs.am = this.getTime('am')
            },
        }"
        x-on:click.stop="focus = true"
        x-on:click.away="focus = false"
        x-bind:class="focus && 'active'"
        {{ $attributes->class(['form-input flex items-center gap-3']) }}
    >
        <x-icon name="clock" class="text-gray-500 shrink-0"/>

        <div class="grow flex items-center justify-evenly gap-2">
            <select x-model="inputs.hour" x-on:input.stop>
                <option selected>--</option>
                @foreach ($format === 24 ? range(0, 23) : range(1, 12) as $n)
                    <option value="{{ $n }}">{{ str()->padLeft($n, 2, '0') }}</option>
                @endforeach
            </select>

            <span class="font-bold">:</span>

            <select x-model="inputs.minute" x-on:input.stop>
                <option selected>--</option>
                @foreach (range(0, 59) as $n)
                    <option value="{{ $n }}">{{ str()->padLeft($n, 2, '0') }}</option>
                @endforeach
            </select>

            <span class="font-bold">:</span>

            @if ($format == 12)
                <select x-model="inputs.am" x-on:input.stop>
                    <option selected>--</option>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
            @else
                <select x-model="inputs.second" x-on:input.stop>
                    <option selected>--</option>
                    @foreach (range(0, 59) as $n)
                        <option value="{{ $n }}">{{ str()->padLeft($n, 2, '0') }}</option>
                    @endforeach
                </select>
            @endif
        </div>
    </div>
</x-form.field>