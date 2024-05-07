<x-form.field {{ $attributes }}>
    <div 
        wire:ignore 
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            show: false,
            date: null,
            hr: null,
            min: null,
            am: null,
            calendar: null,

            get formatted () {
                let parser = dayjs(this.value)
                if (!parser.isValid()) return null

                return parser.format('DD MMM YYYY hh:mm A')
            },
    
            init () {
                this.parse()
                this.$watch('value', (value, old) => {
                    if (value !== old) this.parse()
                })
            },

            parse () {
                let parser = dayjs(this.value)
                let valid = parser.isValid()

                this.date = valid ? parser.format('YYYY-MM-DD') : null
                this.hr = valid ? parser.format('hh') : null
                this.min = valid ? parser.format('mm') : null
                this.am = valid ? parser.format('A') : null
            },
    
            open () {
                this.show = true
                this.$nextTick(() => this.createCalendar())
            },
    
            close () {
                this.show = false
                setTimeout(() => this.destroyCalendar(), 300)
            },

            clear () {
                this.value = null
                this.close()
            },

            setDatetime () {
                if (!this.date && !this.hr && !this.min && !this.am) this.value = null
                else if (!this.date && this.hr === '--' && this.min === '--' && this.am === '--') this.value = null
                else if (!this.date || this.hr === '--' || this.min === '--' || this.am === '--') return

                let datetime = `${this.date} ${this.hr}:${this.min} ${this.am}`
                let parser = dayjs(datetime, 'YYYY-MM-DD h:mm A')

                this.value = parser.isValid() ? parser.utc().toISOString() : null
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
        x-on:keydown.esc.stop="close()"
        x-on:keydown.down="!show && open()"
        {{ $attributes->except(['wire:model', 'wire:model.defer']) }}>
        <button type="button" 
            x-ref="anchor"
            x-on:click="open()"
            class="form-input w-full flex items-center gap-3">
            <div class="shrink-0 text-gray-400"><x-icon name="calendar"/></div>

            <div class="grow flex items-center gap-2">
                <input type="text" placeholder="{{ tr('app.label.select-datetime') }}" readonly
                    x-bind:value="formatted"
                    class="transparent grow cursor-pointer">
            </div>

            <div class="shrink-0">
                <div
                    x-show="value"
                    x-on:click.stop="clear()"
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
            x-on:input.stop="$nextTick(() => setDatetime())"
            x-transition.opacity.duration.300
            class="bg-white rounded-md shadow-lg border z-10 flex flex-col overflow-hidden">
            <div x-ref="calendar"></div>
            <div class="p-3 border-t mt-1">
                <x-form.field label="app.label.time">
                    <div class="form-input flex items-center gap-3">
                        <div class="shrink-0 text-gray-400">
                            <x-icon name="clock"/>
                        </div>
            
                        <div class="grow flex items-center gap-3">
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
                    </div>            
                </x-form.field>
            </div>
        </div>
    </div>
</x-form.field>
