<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <select {{ $attributes->class(['form-input w-full disabled:cursor-not-allowed disabled:bg-gray-100']) }}>
        <option value="" selected> -- {{ $attributes->get('placeholder') ?? 'Please Select' }} -- </option>
        @foreach (metadata()->countries() as $country)
            <option value="{{ $country->iso_code }}">{{ $country->name }}</option>
        @endforeach
    </select>
</x-form.field>
