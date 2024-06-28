<x-input class="h-px" :attributes="$attributes->merge([
    'label' => 'app.label.time',
])->except('class')">
    <div
        wire:ignore
        x-data="{
            value: @entangle($attributes->wire('model')),
            hr: '00',
            min: '00',
            am: 'AM',

            init () {
                this.parse()
                this.$watch('value', (value, old) => { if (value !== old) this.parse() })
                this.$watch('hr', () => this.setTime())
                this.$watch('min', () => this.setTime())
                this.$watch('am', () => this.setTime())
            },

            parse () {
                let parser = dayjs('1970-01-01 '+this.value)
                this.hr = parser?.isValid() ? parser.format('hh') : '12'
                this.min = parser?.isValid() ? parser.format('mm') : '00'
                this.am = parser?.isValid() ? parser.format('A') : 'AM'
            },

            setTime () {
                this.hr = !+this.hr || this.hr > 12 ? '12' : this.hr.toString().padStart(2, '0')
                this.min = !+this.min || this.min > 59 ? '00' : this.min.toString().padStart(2, '0')
                this.value = `${this.hr}:${this.min} ${this.am}`
                this.$dispatch('input', this.value)
            },
        }"
        x-modelable="value"
        class="w-full h-full px-3 flex items-center gap-3"
        {{ $attributes->except('label') }}>
        <div class="shrink-0 text-gray-400">
            <x-icon name="clock"/>
        </div>

        <div x-on:input.stop class="grow flex items-center gap-2">
            <input type="number" x-model.lazy="hr" maxlength="2" class="appearance-none w-8 text-center no-spinner">
            <span class="font-bold">:</span>
            <input type="number" x-model.lazy="min" maxlength="2" class="appearance-none w-8 text-center no-spinner">
            <input type="text" x-bind:value="am" x-on:input="am = am === 'AM' ? 'PM' : 'AM'" class="appearance-none w-8 text-center">
        </div>
    </div>            
</x-input>
