<div>
    @if ($multiple)
        <x-input.textarea wire:model.debounce.400ms="text" caption="Insert multiple images by separating lines">
            Image URL
        </x-input.textarea>
    @else
        <x-input.text wire:model.debounce.400ms="text">
            Image URL
        </x-input.text>
    @endif

    @if ($urls)
        <x-input.field>
            <x-slot name="label">Preview</x-slot>
        
            <div class="flex flex-wrap items-center space-x-2">
                @foreach ($urls as $url)
                    <div class="w-24 h-24 bg-gray-200 rounded-md overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ $url }}">
                    </div>
                @endforeach
            </div>
        </x-input.field>

        <x-button wire:click="submit" icon="check" color="green">
            Save Image
        </x-button>
    @endif
</div>