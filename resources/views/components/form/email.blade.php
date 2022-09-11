<x-form.field {{ $attributes->only(['label', 'error', 'required', 'caption']) }}>
    <input type="email" {{ $attributes->class([
        'form-input w-full',
        'error' => !empty($attributes->get('error')),
    ]) }}>
</x-form.field>
