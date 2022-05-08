<div class="grid gap-6">
    @if ($multiple)
        <x-form.textarea 
            label="Image URL"
            wire:model.debounce.400ms="text" 
            caption="Insert multiple images by separating lines"
        />
    @else
        <x-form.text 
            label="Image URL"
            wire:model.debounce.400ms="text"
        />
    @endif

    @if ($urls)
        <x-form.field label="Preview">
            <div class="flex flex-wrap items-center space-x-2">
                @foreach ($urls as $url)
                    <div class="w-24 h-24 bg-gray-200 rounded-md overflow-hidden">
                        <img class="w-full h-full object-cover" src="{{ $url }}">
                    </div>
                @endforeach
            </div>
        </x-form.field>

        <div>
            <x-button wire:click="submit" icon="check" color="green">
                Save Image
            </x-button>
        </div>
    @endif
</div>