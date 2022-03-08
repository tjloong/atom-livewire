<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <select {{ $attributes->class(['form-input w-full disabled:cursor-not-allowed disabled:bg-gray-100']) }}>
        <option value=""> -- {{ $attributes->get('placeholder') ?? 'Please Select' }} -- </option>
        <option value="male">Male</option>
        <option value="female">Female</option>
    </select>
</x-input.field>