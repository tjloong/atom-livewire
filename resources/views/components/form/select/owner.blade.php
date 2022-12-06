<x-form.select
    :label="$attributes->get('label', 'Owner')"
    {{ $attributes->except(['label', 'options']) }}
    :options="auth()->user()->account->users()->visible()->get()"
/>
