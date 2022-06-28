@props([
    'class' => [
        'transparent' => 'w-full border-0 p-0 pr-10 focus:ring-0',
        'normal' => 'form-input w-full',
    ],
])

<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ __($label) }}</x-slot:label>
    @endif

    <div 
        x-data="{ focus: false }"
        x-bind:class="focus && 'active'"
        class="form-input w-full flex items-center gap-2"
    >
        <input type="number"
            x-on:focus="focus = true"
            x-on:blur="focus = false"
            class="appearance-none bg-transaprent border-0 p-0 focus:ring-0 w-full"
            {{ $attributes->except(['error', 'caption']) }}
        >

        @if ($unit = $attributes->get('unit'))
            <div class="font-medium text-gray-500">{{ __($unit) }}</div>
        @endif
    </div>
</x-form.field>