@props([
    'getValue' => function($key) use ($attributes) {
        if (
            $val = $attributes->wire('model')->value()
                ? data_get($this, $attributes->wire('model')->value())
                : $attributes->get('value')
        ) {
            if ($key === 'date') return format_date($val, 'carbon')->toDateString();
            if ($key === 'time') return format_date($val, 'carbon')->toTimeString();
        }

        return null;
    },
])

<x-form.field {{ $attributes }}>
    <div 
        x-data="{
            date: @js($getValue('date')),
            time: @js($getValue('time')),
            init () {
                this.$watch('date', (val) => this.format())
                this.$watch('time', (val) => this.format())
            },
            setDate (val) {
                if (val === undefined) return
                this.date = val
            },
            setTime (val) {
                if (val === undefined) return
                this.time = val
            },
            format () {
                const value = [this.date, this.time].join(' ')
                const date = dayjs(value, 'YYYY-MM-DD HH:mm:ss')

                this.$dispatch('input', date.utc().format('YYYY-MM-DD HH:mm:ss'))
            },
        }"
        {{ $attributes->class(['flex items-center flex-wrap gap-2 w-full'])->except('value') }}
    >
        <div x-on:input.stop="setDate($event.detail)" class="shrink-0 grow">
            <x-form.date :value="$getValue('date')"/>
        </div>
        <div x-on:input.stop="setTime($event.detail)" class="shrink-0 grow">
            <x-form.time :value="$getValue('time')" :format="$attributes->get('format', 24)"/>
        </div>
    </div>
</x-form.field>