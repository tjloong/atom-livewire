<x-form.field :label="false" {{ $attributes }}>
    <div class="{{ component_error(optional($errors), $attributes) ? 'p-2 rounded form-input-error' : null }}">
        <x-form.checkbox class="text-gray-500"
            :label="$attributes->get('label', 'common.label.agree-marketing')"
            {{ $attributes->except(['class', 'label']) }}/>
    </div>
</x-form.field>
