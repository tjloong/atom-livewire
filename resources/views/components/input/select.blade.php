<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <select {{ $attributes->class(['form-input w-full disabled:cursor-not-allowed disabled:bg-gray-100']) }}>
        <option value="" selected> -- {{ $attributes->get('placeholder') ?? 'Please Select' }} -- </option>
        @foreach ($options as $opt)
            <option value="{{ $opt->value }}">
                {{ $opt->label ?? $opt->value }}
            </option>
        @endforeach
    </select>
</x-input.field>