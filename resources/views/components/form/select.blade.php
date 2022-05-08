<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <select {{ $attributes->class(['form-input w-full disabled:cursor-not-allowed disabled:bg-gray-100']) }}>
        <option value="" selected> -- {{ __($attributes->get('placeholder') ?? 'Please Select') }} -- </option>
        @foreach ($options as $opt)
            <option value="{{ $opt->value }}">
                {{ $opt->label ?? $opt->value }}
            </option>
        @endforeach
    </select>
</x-form.field>