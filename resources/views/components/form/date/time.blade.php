<x-form.field {{ $attributes }}>
    <div
        wire:ignore
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            hr: null,
            min: null,
            am: null,

            init () {
                this.parse()
                this.$watch('value', (value, old) => {
                    if (value !== old) this.parse()
                })
            },

            parse () {
                let parser = dayjs(`1970-01-01 ${this.value}`)
                let valid = parser.isValid()
                
                this.hr = valid ? parser.format('hh') : null
                this.min = valid ? parser.format('mm') : null
                this.am = valid ? parser.format('A') : null
            },

            setTime () {
                if (!this.hr && !this.min && !this.am) this.value = null
                else if (this.hr === '--' && this.min === '--' && this.am === '--') this.value = null
                else if (this.hr === '--' || this.min === '--' || this.am === '--') return

                let time = `${this.hr}:${this.min} ${this.am}`
                let parser = dayjs(`1970-01-01 ${time}`, 'YYYY-MM-DD h:mm A')

                this.value = parser.isValid() ? parser.format('HH:mm:ss') : null
            },

            clear () {
                this.hr = null
                this.min = null
                this.am = null
                this.setTime()
            },
        }"
        x-modelable="value"
        tabindex="0"
        class="{{ $attributes->get('class', 'form-input') }}"
        {{ $attributes->except(['class', 'wire:model', 'wire:model.defer']) }}>
        <div class="flex items-center gap-3">
            <div class="shrink-0 text-gray-400">
                <x-icon name="clock"/>
            </div>

            <div x-on:input.stop="$nextTick(() => setTime())" class="grow flex items-center gap-3">
                <select x-model="hr" class="appearance-none">
                    <option selected>--</option>
                    @foreach (range(1, 12) as $n)
                        <option>{{ str()->padLeft($n, 2, '0') }}</option>
                    @endforeach
                </select>

                <span class="font-bold">:</span>

                <select x-model="min" class="appearance-none">
                    <option selected>--</option>
                    @foreach (range(0, 59) as $n)
                        <option>{{ str()->padLeft($n, 2, '0') }}</option>
                    @endforeach
                </select>

                <span class="font-bold">:</span>

                <select x-model="am" class="appearance-none">
                    <option selected>--</option>
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>
            </div>

            <div
                x-show="value"
                x-on:click="clear()"
                class="shrink-0 text-gray-400 hover:text-gray-600 cursor-pointer">
                <x-icon name="xmark"/>
            </div>
        </div>
    </div>
</x-form.field>