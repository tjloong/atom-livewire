@props(['uid' => uniqid()])

<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    @if ($slot->isNotEmpty())
        <x-slot name="label">{{ $slot }}</x-slot>
    @endif

    <div wire:ignore x-data="{ value: $wire.get('{{ $attributes->wire('model')->value() }}') }">        
        <div 
            x-data="inputRichtext(@js($toolbar), '{{ $attributes->get('placeholder') }}')"
            data-uid="{{ $uid }}"
            class="{{ $attributes->get('class') }}"
        >
            <div {{ $attributes }}>
                <textarea x-ref="input" x-on:change="$dispatch('input', $event.target.value)" class="hidden"></textarea>
            </div>

            <div x-show="loading" class="p-4 flex items-center">
                <x-loader/>
                <div class="font-medium">Loading Editor</div>
            </div>
            
            <div x-ref="ckeditor" x-show="!loading" wire:ignore></div>

            @livewire('input.file', [
                'uid' => $uid,
                'title' => 'Insert Image',
                'accept' => ['image'],
                'sources' => ['device', 'image', 'library'],
            ], key($uid))
        </div>
    </div>
</x-input.field>
