@php
    $config = $attributes->get('config', []);
@endphp

<div
    x-data="{
        value: null,
        config: @js($config),
    }"
    x-modelable="value"
    {{ $attributes->except('config') }}>
    <div 
        x-data="{ calendar: null }" 
        x-init="() => {
            $nextTick(() => {
                calendar = flatpickr($el, {
                    inline: true,
                    dateFormat: 'Y-m-d',
                    defaultDate: value,
                    ...config,
                    onChange: (date, str) => {
                        if (config.mode === 'range') {
                            let from = date[0] ? dayjs(date[0]).format('YYYY-MM-DD') : null
                            let to = date[1] ? dayjs(date[1]).format('YYYY-MM-DD') : null
                            if (from && to) value = `${from} to ${to}`
                        }
                        else value = str
                    },
                })
            })

            $watch('value', () => calendar?.setDate(value))
        }"
        x-on:input.stop>
    </div>
</div>