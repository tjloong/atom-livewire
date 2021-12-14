@props(['uid' => uniqid()])

<x-input.field>
    <x-slot name="label">{{ $label ?? null }}</x-slot>

    <div
        x-data="inputRichtext(@js($toolbar), '{{ $attributes->get('placeholder') }}')" 
        data-uid="{{ $uid }}"
        class="
            {{ $attributes->get('class') }}
            {{ isset($label) ? 'border border-gray-300 rounded-md ring-gray-200 hover:ring-2' : '' }}
        "
    >
        <div {{ $attributes }} x-init="$watch('value', value => $dispatch('input', value))"></div>
    
        <div x-show="loading" class="p-4 flex items-center">
            <x-loader/>
            <div class="font-medium">Loading Editor</div>
        </div>
        
        <div 
            x-ref="ckeditor" 
            x-show="!loading" 
            wire-ignore
            class="min-h-[250px] w-full prose prose-sm max-w-none"
        >
            {{ $slot }}
        </div>
    
        @livewire('input.file', [
            'uid' => $uid,
            'title' => 'Insert Image',
            'accept' => ['image'],
            'sources' => ['device', 'image', 'library'],
        ], key($uid))
    </div>
</x-input.field>
