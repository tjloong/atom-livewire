<x-form.select
    :label="$attributes->get('label', 'Owner')"
    {{ $attributes->except(['label', 'options']) }}
    :options="model('user')->visible()->get()"
/>
