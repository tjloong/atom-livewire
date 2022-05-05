<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    @if ($slot->isNotEmpty())
        <x-slot:label>{{ $slot }}</x-slot:label>
    @endif

    <div wire:ignore x-data="richtextInput(@js([
        'uid' => $uid,
        'model' => $attributes->wire('model')->value(),
        'value' => $attributes->get('value'),
        'toolbar' => $toolbar,
        'placeholder' => $attributes->get('placeholder') ?? __('Your content...'),
    ]))" class="{{ $attributes->get('class') }}">
        <textarea x-ref="input" x-bind:value="value" class="hidden" {{ $attributes->whereStartsWith('wire') }}></textarea>

        <div x-show="loading" class="h-80 p-4">
            <div class="flex items-center">
                <x-loader/>
                <div class="font-medium">Loading Editor</div>
            </div>
        </div>

        <div x-ref="ckeditor" x-show="!loading"></div>
    </div>

    @livewire('atom.app.file.uploader', [
        'uid' => $uid.'-uploader',
        'title' => 'Insert Media',
        'accept' => ['image', 'video', 'audio', 'youtube'],
        'sources' => ['device', 'web-image', 'youtube', 'library'],
    ], key($uid.'-uploader'))
</x-input.field>
