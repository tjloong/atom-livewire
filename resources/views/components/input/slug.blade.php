<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div
        x-data="{ active: false }"
        x-bind:class="active && 'active'"
        class="flex items-center form-input w-full"
    >
        @if ($attributes->has('prefix'))
            <div class="bg-gray-100 rounded-l-md -my-1.5 -ml-3 py-1.5 px-3 border-r border-gray-300 text-gray-400">
                {{ $attributes->get('prefix') }}
            </div>
        @endif

        <input
            x-on:focus="active = true"
            x-on:blur="active = false"
            type="text"
            class="p-0 border-0 text-sm w-full appearance-none px-3 focus:ring-0"
            {{ $attributes->except(['error', 'required', 'caption', 'prefix']) }}
        >
    </div>
</x-input.field>