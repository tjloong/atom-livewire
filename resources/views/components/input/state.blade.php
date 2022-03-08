<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <select {{ $attributes->class(['form-input w-full']) }}>
        @if ($country)
            <option value=""> -- {{ $attributes->get('placeholder') ?? 'Please Select' }} -- </option>
            @foreach (metadata()->states($country) as $state)
                <option>{{ $state['name'] }}</option>
            @endforeach
        @else
            <option value=""> -- Please select a country first -- </option>
        @endif
    </select>
</x-input.field>