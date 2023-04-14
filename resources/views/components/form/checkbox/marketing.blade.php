<x-form.field :label="false" {{ $attributes }}>
    <div class="{{ component_error(optional($errors), $attributes) ? 'p-2 rounded form-input-error' : null }}">
        <x-form.checkbox class="text-gray-500"
            :label="$attributes->get('label', 'I agree to be part of the website\'s database for future marketing and promotional opportunities.')"
            {{ $attributes->except(['class', 'label']) }}
        />
    </div>
</x-form.field>
