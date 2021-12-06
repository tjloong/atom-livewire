@props(['uid' => uniqid()])

<div x-data="inputRichtext(@js($toolbar), '{{ $attributes->get('placeholder') }}')" class="{{ $attributes->get('class') }}" data-uid="{{ $uid }}">
    <div {{ $attributes }} x-init="$watch('value', value => $dispatch('input', value))"></div>

    <div x-show="loading" class="px-4 flex items-center">
        <x-loader/>
        <div class="font-medium">Loading Editor</div>
    </div>
    
    <div x-ref="ckeditor" wire-ignore x-show="!loading" class="min-h-[250px] w-full prose prose-sm max-w-none">
        {{ $slot }}
    </div>

    @livewire('input.file', [
        'uid' => $uid,
        'title' => 'Insert Image',
        'accept' => ['image'],
        'sources' => ['device', 'image', 'library'],
    ], key($uid))
</div>
