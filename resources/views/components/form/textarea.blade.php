<x-form.field {{ $attributes->only(['error', 'required', 'caption', 'label']) }}>
    <textarea {{ 
        $attributes->class([
            'form-input w-full',
            'error' => !empty($attributes->get('error')),
        ])->merge(['rows' => 5])
    }}></textarea>
</x-form.field>
