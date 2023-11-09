@php
    $wire = $attributes->wire('model')->value();
@endphp

<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            time: null, 
            focus: false,
            hour: null,
            minute: null,
            second: null,
            am: null,
            parseTime () {
                let parser = dayjs(`1970-01-01 ${this.time}`)

                if (parser.isValid()) {
                    this.hour = parser.format('hh')
                    this.minute = parser.format('mm')
                    this.second = parser.format('ss')
                    this.am = parser.format('A')
                }
            },
            setTime () {
                const time = `${this.hour}:${this.minute} ${this.am}`
                const parser = dayjs(`1970-01-01 ${time}`, 'YYYY-MM-DD h:mm A')

                if (parser.isValid()) this.time = parser.format('HH:mm:ss')
                else if (this.hour === '--' && this.minute === '--') this.time = null
            },
        }"
        x-init="() => {
            if (@js($wire)) time = $wire.get(@js($wire))
            
            parseTime()
            
            $watch('time', () => {
                parseTime()
                $dispatch('input', time)
                if (!time && @js($wire)) $wire.set(@js($wire), null)
            })
        }"
        x-modelable="time"
        x-on:click.stop="focus = true"
        x-on:click.away="focus = false"
        x-bind:class="focus && 'active'"
        class="{{ $attributes->get('class', 'form-input w-full') }}"
        {{ $attributes->except('class') }}>
        <div class="flex items-center gap-3">
            <div class="shrink-0 text-gray-400">
                <x-icon name="clock"/>
            </div>

            <div x-on:input.stop="$nextTick(() => setTime())" class="grow flex items-center justify-evenly gap-2">
                <select x-model="hour">
                    <option selected>--</option>
                    @foreach (range(1, 12) as $n)
                        <option>{{ str()->padLeft($n, 2, '0') }}</option>
                    @endforeach
                </select>
    
                <span class="font-bold">:</span>
    
                <select x-model="minute">
                    <option selected>--</option>
                    @foreach (range(0, 59) as $n)
                        <option>{{ str()->padLeft($n, 2, '0') }}</option>
                    @endforeach
                </select>
    
                <span class="font-bold">:</span>
    
                <select x-model="am">
                    <option selected>--</option>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
            </div>
        </div>
    </div>
</x-form.field>