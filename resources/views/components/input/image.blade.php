@props(['uid' => uniqid()])

<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot name="label">
        {{ $slot }}
    </x-slot>

    <div x-data="{ value: $wire.get('{{ $attributes->wire('model')->value() }}'), placeholder: @js($placeholder) }">
        <div {{ $attributes }}>
            <input x-ref="input" type="text" x-model="value" x-on:change="$dispatch('input', value)" class="hidden">
        </div>

        <div x-data="inputImage" style="width: {{ $dimension->width }}px; height: {{ $dimension->height }}px;">
            <div
                x-show="placeholder"
                class="w-full h-full relative drop-shadow bg-gray-100 rounded-md overflow-hidden {{ $attributes->has('circle') ? 'rounded-full' : 'rounded-md' }}"
            >
                <img x-bind:src="placeholder" class="w-full h-full object-cover">
                <a x-on:click.prevent="clear()" class="absolute inset-0 opacity-0 hover:opacity-100">
                    <div class="absolute inset-0 bg-black opacity-50"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-white">
                        <x-icon name="x-circle" size="40px"/>
                    </div>
                </a>
            </div>
    
            <div
                x-show="!placeholder"
                x-on:click="$dispatch('file-manager-{{ $uid }}-open')"
                x-on:file-manager-{{ $uid }}-completed.window="select($event.detail[0])"
                class="w-full h-full border-4 border-dashed border-gray-400 text-gray-400 cursor-pointer flex items-center justify-center {{ $attributes->has('circle') ? 'rounded-full' : 'rounded-md' }}"
            >
                <x-icon name="image-add" size="40px"/>
            </div>
        </div>
    </div>

    @livewire('input.file', [
        'uid' => $uid,
        'title' => 'Insert Image',
        'accept' => ['image'],
        'sources' => ['device', 'image', 'library'],
    ])
</x-input.field>
