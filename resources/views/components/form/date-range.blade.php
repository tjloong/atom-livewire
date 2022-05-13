<div 
    x-data="{
        show: false,
        value: @js($attributes->get('value')) || @entangle($attributes->wire('model')),
        calendar: null,
        settings: @js($attributes->get('settings')),
        from: null,
        to: null,
        open () {
            this.show = true
            this.$nextTick(() => {
                this.float()
                this.setCalendar()
            })
        },
        close () {
            if (!this.from || !this.to) return
            this.show = false
            this.calendar.destroy()
            this.calendar = null
        },
        float () {
            floatDropdown(this.$refs.anchor, this.$refs.dd)
        },
        input () {
            if (!this.from || !this.to) return
            this.value = [this.from, this.to]
        },
        setCalendar () {
            if (!this.calendar) {
                this.calendar = flatpickr(this.$refs.calendar, {
                    mode: 'range',
                    inline: true,
                    dateFormat: 'Y-m-d',
                    onChange: (selectedDate, dateStr) => {
                        [from, to] = selectedDate
                        
                        this.from = from ? dayjs(from).format('YYYY-MM-DD') : null
                        this.to = to ? dayjs(to).format('YYYY-MM-DD') : null

                        this.input()
                        this.close()
                    },
                    ...this.settings,
                })
            }

            this.calendar.setDate(this.value || [])
        },
    }"
    x-init="from = (value || [])[0]; to = (value || [])[1]"
    x-on:click.outside="close()"
>
    <div 
        x-ref="anchor" 
        x-bind:class="show && 'active'"
        class="form-input w-full {{ !empty($attributes->get('error')) ? 'error' : '' }}"
    >
        <div class="flex items-center gap-2">
            <x-icon name="calendar" class="text-gray-400" size="16px"/>

            <a x-on:click="open()" class="grow text-gray-900">
                <div class="flex flex-wrap items-center gap-2">
                    <div x-text="formatDate(from) || '{{ __('From') }}'" class="p-1 rounded"></div>
                    <x-icon name="right-arrow-alt"/>
                    <div x-text="formatDate(to) || '{{ __('To') }}'" class="p-1 rounded"></div>
                    <x-icon name="chevron-down"/>
                </div>
            </a>
        </div>
    </div>

    <div
        x-ref="dd"
        x-show="show"
        x-transition.opacity
        class="absolute z-20 bg-white border border-gray-300 shadow-lg rounded-md"
    >
        <div x-ref="calendar"></div>
    </div>

</div>
