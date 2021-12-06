<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div class="relative w-full" x-data="{ show: false }">
        <input
            x-bind:type="show ? 'text' : 'password'"
            {{ $attributes->class(['form-input w-full pr-12']) }}
        >
        <a
            class="absolute top-0 right-0 bottom-0 px-4 flex items-center justify-center text-gray-900"
            @click="show = !show"
        >
            <x-icon x-bind:name="show ? 'hide' : 'show'"/>
        </a>
    </div>
</x-input.field>