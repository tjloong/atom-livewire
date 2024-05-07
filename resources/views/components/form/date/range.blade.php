@php
    $placeholder = $attributes->get('placeholder', 'app.label.select-date-range')
@endphp

<x-form.field {{ $attributes }}>
    <div
        wire:ignore
        x-cloak
        x-data="{
            value: @entangle($attributes->wire('model')),
            show: false,
            focus: false,
            pointer: null,
            calendar: null,

            get from () {
                return this.value?.split(' to ')[0] || null
            },

            get to () {
                return this.value?.split(' to ')[1] || null
            },

            get options () {
                return [
                    { 
                        label: @js(tr('app.label.today')),
                        value: [dayjs(), dayjs()],
                    },
                    { 
                        label: @js(tr('app.label.yesterday')),
                        value: [dayjs().subtract(1, 'day'), dayjs().subtract(1, 'day')],
                    },
                    { 
                        label: @js(tr('app.label.this-month')),
                        value: [dayjs().startOf('month'), dayjs().endOf('month')],
                    },
                    { 
                        label: @js(tr('app.label.this-year')),
                        value: [dayjs().startOf('year'), dayjs().endOf('year')],
                    },
                    { 
                        label: @js(tr('app.label.last-7-days')),
                        value: [dayjs().subtract(6, 'day'), dayjs()],
                    },
                    { 
                        label: @js(tr('app.label.last-30-days')),
                        value: [dayjs().subtract(29, 'day'), dayjs()],
                    },
                    { 
                        label: @js(tr('app.label.last-month')),
                        value: [dayjs().startOf('month').subtract(1, 'day').startOf('month'), dayjs().startOf('month').subtract(1, 'day').endOf('month')],
                    },
                    { 
                        label: @js(tr('app.label.last-year')),
                        value: [dayjs().startOf('year').subtract(1, 'day').startOf('year'), dayjs().startOf('year').subtract(1, 'day').endOf('year')],
                    },
                ].map(opt => {
                    from = opt.value[0].format('YYYY-MM-DD')
                    to = opt.value[1].format('YYYY-MM-DD')

                    return {
                        label: opt.label,
                        value: `${from} to ${to}`
                    }
                })
            },

            open () {
                this.show = true
            },

            close () {
                this.show = false
                this.destroyCalendar()
            },

            select (opt = null) {
                opt = opt || this.options[this.pointer] || null

                if (opt?.value) {
                    this.value = opt.value
                    this.close()
                }
            },

            createCalendar () {
                this.calendar = flatpickr(this.$refs.calendar, {
                    inline: true,
                    dateFormat: 'Y-m-d',
                    defaultDate: this.value,
                    mode: 'range',
                    
                    onReady: () => {
                        this.$root.querySelector('.flatpickr-calendar').style.boxShadow = 'none'
                    },

                    onChange: (date, str) => {
                        let from = date[0] ? dayjs(date[0]).format('YYYY-MM-DD') : null
                        let to = date[1] ? dayjs(date[1]).format('YYYY-MM-DD') : null

                        if (from && to) {
                            this.value = `${from} to ${to}`
                            this.close()
                        }
                    },
                })
            },

            destroyCalendar () {
                this.calendar?.destroy()
                this.calendar = null
            },

            navigate (e) {
                if (e.key === 'ArrowUp' && this.pointer > 0) this.pointer--
                else if (e.key === 'ArrowDown') {
                    if (!this.show) this.open()
                    else if (this.pointer === null) this.pointer = 0
                    else if (this.pointer <= this.options.length - 1) this.pointer++
                    else {
                        this.$root.querySelector('.flatpickr-calendar')?.focus()
                    }
                }
            },
        }"
        x-modelable="value"
        {{ $attributes->except('placeholder') }}>
        <button type="button"
            x-ref="anchor"
            x-on:click.stop="open()"
            x-on:click.away="close()"
            x-on:keydown.esc="close()"
            x-on:keydown.up="navigate"
            x-on:keydown.down="navigate"
            x-on:keydown.enter.prevent="select()"
            class="form-input inline-flex items-center gap-3 min-w-80 w-full">
            <div class="shrink-0 text-gray-400">
                <x-icon name="calendar"/>
            </div>

            <template x-if="from && to">
                <div class="grow flex items-center gap-3">
                    <div x-text="from?.toDateString()" class="grow text-center truncate"></div>
                    <x-icon name="arrow-right" class="shrink-0 text-gray-400"/>
                    <div x-text="to?.toDateString()" class="grow text-center truncate"></div>
                </div>
            </template>

            <template x-if="!from || !to">
                <input type="text" placeholder="{{ tr($placeholder) }}" readonly
                    class="transparent grow cursor-pointer">
            </template>

            <div class="shrink-0">
                <div x-show="value" x-on:click="value = null" class="cursor-pointer text-gray-400 hover:text-gray-600">
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
            class="bg-white rounded-md shadow-lg border z-10 min-w-80 overflow-hidden">
            <div x-show="!calendar" class="flex flex-col">
                <template x-for="(opt, i) in options">
                    <div
                        x-on:click.stop="select(opt)"
                        x-on:mouseover="pointer = i"
                        x-bind:class="pointer === i && 'bg-slate-100'"
                        class="py-2 px-4 hover:bg-slate-50 cursor-pointer border-b">
                        <div x-text="opt.label"></div>
                    </div>
                </template>

                <x-anchor label="app.label.custom-date" icon-suffix="arrow-right"
                    x-on:click.stop="createCalendar()"
                    class="py-2 px-4 text-sm bg-slate-100"/>
            </div>

            <div x-show="calendar" class="flex flex-col divide-y">
                <div x-on:click.stop="destroyCalendar()" class="py-2 px-4 flex items-center gap-3 cursor-pointer hover:bg-slate-50">
                    <x-icon name="arrow-left" class="text-gray-400"/> {{ tr('app.label.preset-date') }}
                </div>

                <div x-on:click.stop x-on:input.stop x-on:keydown.esc="close()">
                    <div x-ref="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</x-form.field>
