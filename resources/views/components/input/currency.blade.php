<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <select {{ $attributes->class(['form-input w-full']) }}>
        <option value="" selected> -- {{ $attributes->get('placeholder') ?? 'Please Select' }} -- </option>
        @foreach ($countries as $country)
            <option value="{{ $country['currency_code'] }}">{{ $country['currency_code'] }} - {{ $country['name'] }}</option>
        @endforeach
    </select>
</x-input.field>