@php
    $file = $attributes->get('file');
@endphp

<div
    x-data="{
        file: @js($file),
    }"
    x-modelable="file"
    x-on:click.stop="Livewire.emit('updateFile', file.id)"
    {{ $attributes->class([
        'flex items-center gap-2 py-2 px-3 cursor-pointer hover:bg-slate-50',
    ])->except('file') }}>
    <div x-show="file.is_file" class="shrink-0 w-6 flex items-center justify-center">
        <x-icon name="file" class="text-gray-400"/>
    </div>

    <figure x-show="file.is_image" class="shrink-0 w-6 h-6 bg-white rounded-md border flex">
        <img x-bind:src="file.url" class="w-full h-full object-cover">
    </figure>

    <div class="grow flex flex-col md:flex-row md:items-center md:gap-2">
        <div class="grow grid">
            <div class="font-medium text-sm truncate" x-text="file.name"></div>
        </div>
    
        <div class="shrink-0 text-sm text-gray-500 font-medium" x-text="file.mime"></div>
    </div>
</div>