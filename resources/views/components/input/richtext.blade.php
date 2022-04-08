<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    @if ($slot->isNotEmpty())
        <x-slot name="label">{{ $slot }}</x-slot>
    @endif

    <div wire:ignore x-data="{ value: $wire.get('{{ $attributes->wire('model')->value() }}') }">  
        <div x-data="richtextInput(@js($toolbar), '{{ $attributes->get('placeholder') }}')" class="{{ $attributes->get('class') }}">
            <div {{ $attributes }}>
                <textarea x-ref="input" x-on:change="$dispatch('input', $event.target.value)" class="hidden"></textarea>
            </div>

            <div x-show="loading" class="h-80 p-4">
                <div class="flex items-center">
                    <x-loader/>
                    <div class="font-medium">Loading Editor</div>
                </div>
            </div>

            <div x-ref="ckeditor" x-show="!loading"></div>

            @livewire('atom.app.file.uploader', [
                'uid' => 'richtext-uploader',
                'title' => 'Insert Media',
                'accept' => ['image', 'video', 'audio', 'youtube'],
                'sources' => ['device', 'web-image', 'youtube', 'library'],
            ], key(uniqid()))
        </div>
    </div>
</x-input.field>
