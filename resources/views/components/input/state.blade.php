<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <select {{ $attributes->class(['form-input w-full']) }}>
        <option value="" selected> -- {{ $attributes->get('placeholder') ?? 'Please Select' }} -- </option>
        <option>Johor</option>
        <option>Kedah</option>
        <option>Kelantan</option>
        <option>Kuala Lumpur</option>
        <option>Labuan</option>
        <option>Melaka</option>
        <option>Negeri Sembilan</option>
        <option>Pahang</option>
        <option>Perak</option>
        <option>Perlis</option>
        <option>Pulau Pinang</option>
        <option>Putrajaya</option>
        <option>Sabah</option>
        <option>Sarawak</option>
        <option>Selangor</option>
        <option>Terengganu</option>
    </select>
</x-input.field>