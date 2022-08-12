<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <div 
        x-data="{
            value: @js($attributes->get('value')) || @entangle($attributes->wire('model')),
            focus: false,
        }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2 {{ !empty($attributes->get('error')) ? 'error' : '' }}"
    >
        @if ($attributes->has('prefix'))
            <div class="text-gray-400 font-medium">
                {{ $attributes->get('prefix') }}
            </div>
        @endif
        
        <input 
            x-model="value"
            x-on:focus="focus = true"
            x-on:blur="focus = false"
            type="number"
            class="form-input transparent grow"
            step=".01"
        >
    </div>
</x-form.field>
