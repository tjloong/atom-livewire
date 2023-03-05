<x-form.field {{ $attributes }}>
    <textarea {{ 
        $attributes->class([
            'form-input w-full',
            'error' => component_error(optional($errors), $attributes),
        ])->merge(['rows' => 5])
    }}></textarea>
</x-form.field>
