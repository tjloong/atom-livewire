@php
    $label = $attributes->get('label', 'common.label.gender');
    $placeholder = $attributes->get('placeholder', 'common.label.select-gender');
@endphp

<x-form.select :label="$label" :placeholder="$placeholder" :search="false"
    :options="collect(['male', 'female'])->map(fn($val) => [
        'value' => $val, 
        'label' => tr('common.label.'.$val),
    ])->toArray()"
    {{ $attributes->except(['options', 'label', 'placeholder']) }}/>
