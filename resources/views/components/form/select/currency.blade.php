@props([
    'id' => $attributes->get('id', 'currency'),
    'label' => $attributes->get('label', 'Currency'),
    'options' => $attributes->get('options'),
])

<x-form.select :id="$id"
    :label="$label"
    :options="
        collect($options ?? currencies())->map(function($val) {
            if (is_string($val)) return ['value' => $val, 'label' => $val];
            
            $code = data_get($val, 'code') ?? data_get($val, 'currency') ?? null;
            $symbol = data_get($val, 'symbol');

            return [
                'value' => $code, 
                'label' => implode(' - ', array_filter([$code, $symbol])),
            ];
        })
            ->reject(fn($val) => empty($val['value']))
            ->unique('value')
            ->sortBy('label')
            ->toArray()
    " {{ $attributes->except('id', 'label', 'options') }}/>
