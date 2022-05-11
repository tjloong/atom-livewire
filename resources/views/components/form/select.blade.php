<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <select {{ $attributes
        ->class('form-input w-full disabled:cursor-not-allowed disabled:bg-gray-100')
        ->except(['error', 'required', 'caption', 'options']) 
    }}>
        <option value="" selected> -- {{ __($attributes->get('placeholder') ?? 'Please Select') }} -- </option>
        @foreach ($attributes->get('options') as $opt)
            <option value="{{ data_get($opt, 'value', $opt) }}">
                {{ data_get($opt, 'label') ?? data_get($opt, 'value') ?? $opt }}
            </option>
        @endforeach
    </select>
</x-form.field>
