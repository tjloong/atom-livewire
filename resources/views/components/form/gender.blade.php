<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <select {{ $attributes->class(['form-input w-full disabled:cursor-not-allowed disabled:bg-gray-100']) }}>
        <option value=""> -- {{ __($attributes->get('placeholder') ?? 'Please Select') }} -- </option>
        <option value="male">{{ __('Male') }}</option>
        <option value="female">{{ __('Female') }}</option>
    </select>
</x-form.field>
