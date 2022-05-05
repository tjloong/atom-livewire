<x-input.field {{ $attributes->filter(fn($val, $key) => in_array($key, ['error', 'required', 'caption'])) }}>
    <x-slot:label>{{ $slot }}</x-slot:label>

    <div x-data="imageInput(@js([
        'model' => $attributes->wire('model')->value(),
        'value' => $attributes->get('value'),
        'shape' => $attributes->get('shape') ?? 'square',
        'placeholder' => $attributes->get('placeholder'),
    ]))">
        <input x-ref="input" type="hidden" x-bind:value="value" {{ $attributes->whereStartsWith('wire') }}>

        <div style="width: {{ $dimension->width }}px; height: {{ $dimension->height }}px;">
            <div
                x-show="placeholder"
                x-bind:class="shape === 'circle' ? 'rounded-full' : 'rounded-md'"
                class="w-full h-full relative drop-shadow bg-gray-100 rounded-md overflow-hidden"
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
                x-on:click="$dispatch('{{ $uid }}-uploader-open')"
                x-on:{{ $uid }}-uploader-completed.window="select($event.detail[0])"
                class="w-full h-full border-4 border-dashed border-gray-400 text-gray-400 cursor-pointer flex items-center justify-center {{ $attributes->has('circle') ? 'rounded-full' : 'rounded-md' }}"
            >
                <x-icon name="image-add" size="40px"/>
            </div>
        </div>
    </div>

    @livewire('atom.app.file.uploader', [
        'uid' => $uid.'-uploader',
        'title' => 'Insert Image',
        'accept' => ['image'],
        'sources' => ['device', 'web-image', 'library'],
    ], key($uid.'-uploader'))
</x-input.field>
