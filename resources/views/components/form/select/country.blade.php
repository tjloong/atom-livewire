<x-form.select 
    :label="$attributes->get('label', 'Country')"
    :options="metadata()->countries()"
    {{ $attributes->except('label', 'options') }}
/>
