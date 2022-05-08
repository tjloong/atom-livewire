<x-form.field {{ $attributes->only(['error', 'required', 'caption']) }}>
    @if ($label = $attributes->get('label'))
        <x-slot:label>{{ $label }}</x-slot:label>
    @endif

    <div class="relative w-full" x-data="{ show: false }">
        <input
            x-bind:type="show ? 'text' : 'password'"
            {{ $attributes->class([
                'form-input w-full pr-12',
                'error' => !empty($attributes->get('error')),
            ]) }}
        >
        <a
            class="absolute top-0 right-0 bottom-0 px-4 flex items-center justify-center text-gray-900"
            x-on:click="show = !show"
        >
            <x-icon x-bind:name="show ? 'hide' : 'show'"/>
        </a>
    </div>
</x-form.field>
