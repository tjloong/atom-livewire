@php
$transparent = $attributes->get('transparent', false);
$placeholder = $attributes->get('placeholder', 'app.label.select-datetime');
$except = ['label', 'for', 'field', 'utc', 'transparent', 'placeholder', 'class'];
@endphp

<x-input class="h-px" :attributes="$attributes->except('class')">
    <div
        wire:ignore
        x-data="{
            show: false,
            value: @entangle($attributes->wire('model')),
            date: null,
            time: null,
            datetime: null,
            calendar: null,

            init () {
                this.parse()
                this.$watch('value', (value, old) => {
                    if (value !== old) this.parse()
                })
            },

            parse () {
                let parser = this.value ? dayjs(this.value) : null
                this.date = parser?.isValid() ? parser.format('YYYY-MM-DD') : null
                this.time = parser?.isValid() ? parser.format('hh:mm A') : null
                this.datetime = parser?.isValid() ? parser.format('DD MMM YYYY hh:mm A') : null
            },

            open () {
                this.show = true
                this.$nextTick(() => this.createCalendar())
            },

            close () {
                this.show = false
                setTimeout(() => this.destroyCalendar(), 300)
            },

            setDatetime () {
                let date = this.date || dayjs().format('YYYY-MM-DD')
                let time = this.time || '12:00 AM'
                let datetime = `${date} ${time}`
                this.value = dayjs(datetime).utc().toISOString()
            },

            createCalendar () {
                this.calendar = flatpickr(this.$refs.calendar, {
                    inline: true,
                    dateFormat: 'Y-m-d',
                    defaultDate: this.date,
                    onReady: () => this.$root.querySelector('.flatpickr-calendar').style.boxShadow = 'none',
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
        class="w-full h-full"
        {{ $attributes->except($except) }}>
        <button type="button"
            x-ref="anchor"
            x-on:click="open()"
            x-on:keydown.esc="close()"
            class="group/button inline-flex items-center gap-3 px-3 w-full h-full text-left">
            <div class="shrink-0 text-gray-400 {{ $transparent ? 'hidden group-hover/button:block group-focus/button:block' : '' }}">
                <x-icon name="calendar-minus"/>
            </div>

            <template x-if="datetime">
                <div class="grow">
                    <div x-text="datetime" class="truncate"></div>
                </div>
            </template>

            <template x-if="!datetime">
                <input type="text" class="grow appearance-none cursor-pointer" placeholder="{{ tr($placeholder) }}" readonly>
            </template>

            <div class="shrink-0">
                <div x-show="value" x-on:click="value = null" class="cursor-pointer text-gray-400 hover:text-gray-600 hidden group-hover/button:block group-focus/button:block">
                    <x-icon name="xmark"/>
                </div>
            </div>

            <div class="shrink-0 w-3 h-full select-caret"></div>
        </button>

        <div
            x-ref="dropdown"
            x-show="show"
            x-anchor.bottom-start.offset.4="$refs.anchor"
            x-transition.opacity.duration.300
            class="bg-white rounded-md shadow-lg border z-10 flex flex-col overflow-hidden">
            <div x-on:input.stop="$nextTick(() => setDatetime())">
                <div x-ref="calendar"></div>
            </div>
            <div x-on:input.stop="$nextTick(() => setDatetime())" class="p-3 border-t mt-1">
                <x-time-picker x-model="time"/>
            </div>
        </div>
    </div>
</x-input>
