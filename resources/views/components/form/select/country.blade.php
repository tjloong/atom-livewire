<x-form.select 
    :label="$attributes->get('label', 'Country')"
    :options="countries()"
    {{ $attributes->except('label', 'options') }}
/>
