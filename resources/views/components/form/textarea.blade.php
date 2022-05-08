<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <textarea {{ 
        $attributes->class([
            'form-input w-full',
            'error' => !empty($attributes->get('error')),
        ])->merge(['rows' => 5])
    }}></textarea>
</x-form.field>
