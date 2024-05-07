<x-form.field {{ $attributes }}>
    <div 
        wire:ignore 
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            show: false,
            date: null,
            time: null,
            calendar: null,

            get formatted () {
                let body = [this.date]

                let time = this.time
                let parser = dayjs(`1970-01-01 ${time}`, 'YYYY-MM-DD HH:mm:ss')
                if (parser.isValid()) body.push(parser.format('hh:mm A'))

                return body.filter(Boolean).join(' ')
            },
    
            init () {
                this.parse()
                this.$watch('date', () => this.setDatetime())
                this.$watch('time', () => this.setDatetime())
                this.$watch('value', (value, old) => {
                    if (value !== old) this.parse()
                })
            },

            parse () {
                let parser = dayjs(this.value)
                let valid = parser.isValid()

                this.date = valid ? parser.format('YYYY-MM-DD') : null
                this.time = valid ? parser.format('HH:mm:ss') : null
            },
    
            open () {
                this.show = true
                this.$nextTick(() => this.createCalendar())
            },
    
            close () {
                this.show = false
                this.$nextTick(() => this.destroyCalendar())
            },

            clear () {
                this.value = null
                this.close()
            },

            setDatetime () {
                if (!this.date && !this.time) this.value = null
                else if (this.date && !this.time) this.date = null
                else if (!this.date && this.time) this.date = dayjs().format('YYYY-MM-DD')
                else {
                    let datetime = dayjs(`${this.date} ${this.time}`, 'YYYY-MM-DD hh:mm A')
                    this.value = datetime.utc().toISOString()
                    this.calendar.setDate(this.date)
                }
            },
    
            createCalendar () {
                this.calendar = flatpickr(this.$refs.calendar, {
                    inline: true,
                    dateFormat: 'Y-m-d',
                    defaultDate: this.date,
                    
                    onReady: () => {
                        this.$root.querySelector('.flatpickr-calendar').style.boxShadow = 'none'
                    },

                    onChange: (date, str) => this.date = str,
                })
            },
    
            destroyCalendar () {
                this.calendar?.destroy()
                this.calendar = null
            },
        }"
        x-modelable="value"
        x-on:click.away="close()"
        x-on:keydown.esc="close()"
        {{ $attributes->except(['wire:model', 'wire:model.defer']) }}>
        <button type="button" 
            x-ref="anchor"
            x-on:click="open()"
            class="form-input w-full flex items-center gap-3">
            <div class="shrink-0 text-gray-400"><x-icon name="calendar"/></div>

            <div class="grow flex items-center gap-2">
                <input type="text" placeholder="{{ tr('app.label.select-date') }}" readonly
                    x-bind:value="formatted"
                    class="transparent grow cursor-pointer">
            </div>

            <div class="shrink-0">
                <div
                    x-show="value"
                    x-on:click="clear()"
                    class="cursor-pointer text-gray-400 hover:text-gray-600">
                    <x-icon name="xmark"/>
                </div>

                <x-icon x-show="!value" name="dropdown-caret"/>
            </div>
        </button>

        <div 
            x-ref="dropdown"
            x-show="show"
            x-anchor.bottom-start.offset.4="$refs.anchor"
            x-on:mouseout="pointer = null"
            x-transition.opacity.duration.300
            class="bg-white rounded-md shadow-lg border z-10 flex flex-col divide-y overflow-hidden">
            <div x-ref="calendar"></div>
            <div x-show="show" class="p-2">
                <x-form.date.time x-model="time"/>
            </div>
        </div>
    </div>
</x-form.field>
