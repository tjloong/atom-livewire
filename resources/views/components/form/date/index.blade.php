@php
    $time = $attributes->get('time', false);
    $range = $attributes->get('range', false);
    $placeholder = $attributes->get('placeholder', collect([
        'common.label.select-date' => !$time && !$range,
        'common.label.select-datetime' => $time && !$range,
        'common.label.select-date-range' => $range,
    ])->filter()->keys()->first());
@endphp

<x-form.field {{ $attributes }}>
@if ($range)
    <div wire:ignore x-cloak
        x-data="{
            range: @entangle($attributes->wire('model')),
            show: false,
            get from () { return this.range ? this.range.split(' to ')[0] : null },
            get to () { return this.range ? this.range.split(' to ')[1] : null },
            preset (opt) {
                let from = {
                    today: dayjs(),
                    yesterday: dayjs().subtract(1, 'day'),
                    'this-month': dayjs().startOf('month'),
                    'this-year': dayjs().startOf('year'),
                    'last-7-days': dayjs().subtract(6, 'day'),
                    'last-30-days': dayjs().subtract(29, 'day'),
                    'last-month': dayjs(`${dayjs().get('year')}-${dayjs().get('month')}-01`, 'YYYY-MM-DD'),
                    'last-year': dayjs(`${dayjs().get('year')-1}-01-01`, 'YYYY-MM-DD'),
                }[opt]

                let to = {
                    today: dayjs(),
                    yesterday: dayjs().subtract(1, 'day'),
                    'this-month': dayjs().endOf('month'),
                    'this-year': dayjs().endOf('year'),
                    'last-7-days': dayjs(),
                    'last-30-days': dayjs(),
                    'last-month': from.endOf('month'),
                    'last-year': from.endOf('year'),
                }[opt]

                from = from.format('YYYY-MM-DD')
                to = to.format('YYYY-MM-DD')
                this.range = `${from} to ${to}`
            }
        }"
        x-modelable="range"
        x-init="$watch('range', () => show = false)"
        x-on:click.away="show = false"
        x-bind:class="show && 'active'"
        {{ $attributes
            ->merge(['class' => 'form-input w-full relative'])
            ->except(['time', 'range', 'placeholder']) }}>
        <button type="button" class="flex items-center gap-3 w-full"
            x-on:click.stop="show = true">
            <div class="shrink-0 text-gray-400">
                <x-icon name="calendar"/>
            </div>
    
            <template x-if="from && to">
                <div class="grow flex items-center gap-3">
                    <div x-text="formatDate(from)" class="grow text-center truncate"></div>
                    <x-icon name="arrow-right" class="shrink-0"/>
                    <div x-text="formatDate(to)" class="grow text-center truncate"></div>
                </div>
            </template>

            <template x-if="!from || !to">
                <input type="text" placeholder="{{ tr($placeholder) }}" readonly
                    class="transparent grow cursor-pointer">
            </template>
    
            <div class="shrink-0">
                <x-close x-show="range" x-on:click="range = null"/>
                <x-icon x-show="!range" name="dropdown-caret"/>
            </div>
        </button>

        <div 
            x-ref="dd"
            x-show="show"
            x-transition
            class="absolute left-0 top-full mt-px z-20 w-max bg-white rounded-md shadow-lg border">
            <div class="flex flex-col divide-y">
                @foreach ([
                    'today',
                    'yesterday',
                    'this-month',
                    'this-year',
                    'last-7-days',
                    'last-30-days',
                    'last-month',
                    'last-year',
                ] as $opt)
                    <div 
                        x-on:click="preset(@js($opt))"
                        class="py-2 px-4 hover:bg-slate-50 cursor-pointer">
                        {{ tr('common.label.'.$opt) }}
                    </div>
                @endforeach

                <div class="py-2">
                    <x-form.date.picker x-model="range" :config="['mode' => 'range']"/>
                </div>
            </div>
        </div>
    </div>
@elseif ($time)
    <div wire:ignore x-cloak
        x-data="{
            show: false,
            datetime: @entangle($attributes->wire('model')),
            get formatted () {
                if (!this.datetime) return ''
                return dayjs(this.datetime).format('DD MMM YYYY h:mm A')
            },
        }"
        x-modelable="datetime"
        x-on:click.away="show = false"
        x-on:datetime-updated="(e) => {
            let dt = dayjs(`${e.detail.date} ${e.detail.time}`)
            
            if (!dt.isValid() || dt.isSame(dayjs(datetime))) return

            datetime = dt.utc().toISOString()
        }"
        x-bind:class="show && 'active'"
        {{ $attributes->merge(['class' => 'form-input w-full relative']) }}>
        <button type="button" class="flex items-center gap-3 w-full"
            x-on:click.stop="show = true">
            <div class="shrink-0 text-gray-400">
                <x-icon name="calendar"/>
            </div>
    
            <input type="text" placeholder="{{ tr($placeholder) }}" readonly
                x-bind:value="formatted"
                class="transparent grow cursor-pointer">
    
            <div class="shrink-0">
                <x-icon name="dropdown-caret"/>
            </div>
        </button>

        <div 
            x-ref="dd"
            x-show="show"
            x-transition
            class="absolute left-0 top-full mt-px z-10 w-max bg-white rounded-md shadow-lg border">
            <div
                x-data="{
                    inputs: {
                        date: null,
                        time: null,
                    },
                }"
                x-init="() => {
                    let dt = dayjs(datetime)
                    if (dt.isValid()) {
                        inputs.date = dt.format('YYYY-MM-DD')
                        inputs.time = dt.format('HH:mm:ss')
                    }
                }"
                x-on:input.stop="$dispatch('datetime-updated', inputs)"
                class="flex flex-col divide-y">
                <div class="py-2">
                    <x-form.date.picker x-model="inputs.date"/>
                </div>

                <div class="p-3">
                    <x-form.date.time x-model="inputs.time"/>
                </div>
            </div>
        </div>
    </div>
@else
    <div wire:ignore x-cloak
        x-data="{
            date: @entangle($attributes->wire('model')),
            show: false,
        }"
        x-modelable="date"
        x-on:click.away="show = false"
        x-bind:class="show && 'active'"
        {{ $attributes->merge(['class' => 'form-input w-full relative']) }}>
        <button type="button" class="flex items-center gap-3 w-full"
            x-on:click.stop="show = true">
            <div class="shrink-0 text-gray-400">
                <x-icon name="calendar"/>
            </div>
    
            <input type="text" placeholder="{{ tr($placeholder) }}" readonly
                x-bind:value="formatDate(date)"
                class="transparent grow cursor-pointer">
    
            <div class="shrink-0">
                <x-icon name="dropdown-caret"/>
            </div>
        </button>

        <div 
            x-ref="dd"
            x-show="show"
            x-on:input.stop="show = false"
            x-transition
            class="absolute left-0 top-full mt-px z-10 w-max bg-white rounded-md shadow-lg border">
            <x-form.date.picker x-model="date"/>
        </div>
    </div>
@endif
</x-form.field>