@php
$utc = $attributes->get('no-utc') ? false : $attributes->get('utc', true);
$mode = $attributes->get('mode', 'single');
$transparent = $attributes->get('transparent', false);
$placeholder = $attributes->get('placeholder', 'app.label.select-date');
$except = ['label', 'for', 'field', 'utc', 'mode', 'transparent', 'placeholder', 'class'];
@endphp

<x-input class="h-px" {{ $attributes->except('class') }}>
    <div
        wire:ignore
        x-data="{
            utc: @js($utc),
            show: false,
            mode: @js($mode),
            value: @entangle($attributes->wire('model')),
            pointer: null,
            calendar: null,
            transparent: @js($transparent),

            get options () {
                if (this.mode === 'single') return []

                return [
                    { 
                        label: @js(tr('app.label.today')),
                        value: [dayjs().startOf('day'), dayjs().endOf('day')],
                    },
                    { 
                        label: @js(tr('app.label.yesterday')),
                        value: [dayjs().subtract(1, 'day').startOf('day'), dayjs().subtract(1, 'day').endOf('day')],
                    },
                    { 
                        label: @js(tr('app.label.this-month')),
                        value: [dayjs().startOf('month').startOf('day'), dayjs().endOf('month').endOf('day')],
                    },
                    { 
                        label: @js(tr('app.label.this-year')),
                        value: [dayjs().startOf('year').startOf('day'), dayjs().endOf('year').endOf('day')],
                    },
                    { 
                        label: @js(tr('app.label.last-7-days')),
                        value: [dayjs().subtract(6, 'day').startOf('day'), dayjs().endOf('day')],
                    },
                    { 
                        label: @js(tr('app.label.last-30-days')),
                        value: [dayjs().subtract(29, 'day').startOf('day'), dayjs().endOf('day')],
                    },
                    { 
                        label: @js(tr('app.label.last-month')),
                        value: [dayjs().startOf('month').subtract(1, 'day').startOf('month').startOf('day'), dayjs().startOf('month').subtract(1, 'day').endOf('month').endOf('day')],
                    },
                    { 
                        label: @js(tr('app.label.last-year')),
                        value: [dayjs().startOf('year').subtract(1, 'day').startOf('year').startOf('day'), dayjs().startOf('year').subtract(1, 'day').endOf('year').endOf('day')],
                    },
                ].map(opt => {
                    from = this.utc ? opt.value[0].utc() : opt.value[0]
                    to = this.utc ? opt.value[1].utc() : opt.value[1]

                    return {
                        label: opt.label,
                        value: `${from.format('YYYY-MM-DD HH:mm:ss')} to ${to.format('YYYY-MM-DD HH:mm:ss')}`
                    }
                })
            },

            open () {
                if (this.show) return
                this.show = true
                if (this.mode === 'single') this.createCalendar()
            },

            close () {
                this.show = false
                this.destroyCalendar()
            },

            selectOption (opt = null) {
                opt = opt || this.options[this.pointer] || null

                if (opt?.value) {
                    this.value = opt.value
                    this.close()
                }
            },

            createCalendar () {
                this.$nextTick(() => {
                    let from = this.getFrom('YYYY-MM-DD HH:mm:ss')
                    let to = this.getTo('YYYY-MM-DD HH:mm:ss')

                    this.calendar = flatpickr(this.$refs.calendar, {
                        inline: true,
                        mode: this.mode,
                        dateFormat: 'Y-m-d',
                        defaultDate: this.mode === 'single' ? from : (
                            from && to ? `${from} to ${to}` : null
                        ),
                        onReady: () => this.$root.querySelector('.flatpickr-calendar').style.boxShadow = 'none',
                        onChange: (date, str) => this.onChange(date, str),
                    })
                })
            },

            onChange (date, str) {
                if (this.mode === 'range') {
                    let from = date[0] ? dayjs(date[0]).startOf('day').utc().format('YYYY-MM-DD HH:mm:ss') : null
                    let to = date[1] ? dayjs(date[1]).endOf('day').utc().format('YYYY-MM-DD HH:mm:ss') : null

                    if (from && to) {
                        this.value = `${from} to ${to}`
                        this.close()
                    }
                }
                else {
                    this.value = dayjs(str).startOf('day').utc().format('YYYY-MM-DD HH:mm:ss')
                    this.close()
                }
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

            getFrom (format = 'DD MMM YYYY') {
                let from

                if (this.mode === 'range') from = this.value ? dayjs(this.value.split(' to ')[0]) : null
                if (this.mode === 'single') from = this.value ? dayjs(this.value) : null
                if (this.utc && from) from = from.utc(true).tz(dayjs.tz.guess())

                return from?.isValid() ? from?.format(format) : null
            },

            getTo (format = 'DD MMM YYYY') {
                if (this.mode === 'single') return null

                let to = this.value ? dayjs(this.value.split(' to ')[1]) : null
                if (this.utc && to) to = to.utc(true).tz(dayjs.tz.guess())
                return to?.isValid() ? to?.format(format) : null
            },
        }"
        x-modelable="value"
        class="w-full h-full"
        {{ $attributes->except($except) }}>
        <button type="button"
            x-ref="anchor"
            x-on:click="open()"
            x-on:click.away="close()"
            x-on:keydown.esc="close()"
            x-on:keydown.up="navigate"
            x-on:keydown.down="navigate"
            x-on:keydown.enter.prevent="selectOption()"
            class="group/button inline-flex items-center gap-3 px-3 w-full h-full text-left focus:outline-none">
            <div class="shrink-0 text-gray-400 {{ $transparent ? 'hidden group-hover/button:block group-focus/button:block' : '' }}">
                <x-icon name="calendar-days"/>
            </div>

            <template x-if="getFrom() && getTo()">
                <div class="grow flex items-center gap-3">
                    <div x-text="getFrom()" class="truncate"></div>
                    <x-icon name="arrow-right" class="shrink-0 text-gray-400"/>
                    <div x-text="getTo()" class="truncate"></div>
                </div>
            </template>

            <template x-if="getFrom() && !getTo()">
                <div class="grow">
                    <div x-text="getFrom()" class="truncate"></div>
                </div>
            </template>

            <template x-if="!getFrom() && !getTo()">
                <input type="text" placeholder="{{ tr($placeholder) }}" readonly class="transparent grow cursor-pointer">
            </template>

            <div class="shrink-0">
                <div x-show="value" x-on:click="value = null" class="cursor-pointer text-gray-400 hover:text-gray-600 hidden group-hover/button:block group-focus/button:block">
                    <x-icon name="xmark"/>
                </div>
            </div>

            <div class="shrink-0 w-3 h-full flex items-center justify-center">
                <x-icon dropdown/>
            </div>
        </button>

        <div 
            x-ref="dropdown"
            x-show="show"
            x-anchor.bottom-start.offset.4="$refs.anchor"
            x-on:mouseout="pointer = null"
            x-transition.opacity.duration.300
            class="bg-white rounded-md shadow-lg border z-10 min-w-80 overflow-hidden">
            <template x-if="options.length">
                <div x-show="!calendar" class="flex flex-col">
                    <template x-for="(opt, i) in options">
                        <div
                            x-on:click.stop="selectOption(opt)"
                            x-on:mouseover="pointer = i"
                            x-bind:class="pointer === i && 'bg-slate-100'"
                            class="py-2 px-4 hover:bg-slate-50 cursor-pointer border-b">
                            <div x-text="opt.label"></div>
                        </div>
                    </template>

                    <x-anchor label="app.label.custom-date" icon-suffix="arrow-right"
                        x-on:click.stop="createCalendar()"
                        class="py-2 px-4 text-sm bg-slate-100">
                    </x-anchor>
                </div>
            </template>

            <div x-show="calendar" class="flex flex-col divide-y">
                <template x-if="options.length">
                    <div x-on:click.stop="destroyCalendar()" class="py-2 px-4 flex items-center gap-3 cursor-pointer hover:bg-slate-50">
                        <x-icon name="arrow-left" class="text-gray-400"/> {{ tr('app.label.preset-date') }}
                    </div>
                </template>

                <div x-on:click.stop x-on:input.stop x-on:keydown.esc="close()">
                    <div x-ref="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</x-input>
