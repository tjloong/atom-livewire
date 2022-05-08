<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <select {{ $attributes->class(['form-input w-full']) }}>
        @if ($country = $attributes->get('country'))
            <option value=""> -- {{ __($attributes->get('placeholder') ?? 'Please Select') }} -- </option>
            @foreach (metadata()->states($country) as $state)
                <option>{{ $state->name }}</option>
            @endforeach
        @else
            <option value=""> -- {{ __('Please select a country first') }} -- </option>
        @endif
    </select>
</x-form.field>
