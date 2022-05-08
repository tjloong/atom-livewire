<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <input type="email" {{ $attributes->class([
        'form-input w-full',
        'error' => !empty($attributes->get('error')),
    ]) }}>
</x-form.field>
