<div>
    @if ($multiple)
        <x-input.textarea wire:model.debounce.400ms="text" caption="Insert multiple Youtube videos by separating lines">
            Youtube URL
        </x-input.textarea>
    @else
        <x-input.text wire:model.debounce.400ms="text">
            Youtube URL
        </x-input.text>
    @endif

    @if ($urls)
        <x-input.field>
            <x-slot name="label">Preview</x-slot>
            <div class="flex flex-wrap items-center space-x-2">
                @foreach ($urls as $url)
                    <div class="relative w-24 h-24 bg-gray-200 rounded-md overflow-hidden">
                        <div class="absolute inset-0">
                            <img src="{{ $url['tn'] }}" class="w-full h-full object-cover">
                        </div>
                        
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="bg-white w-4 h-4"></div>
                        </div>
                        
                        <div class="absolute inset-0 flex justify-center items-center text-red-500">
                            <x-icon name="youtube" type="logo" size="32px"/>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-input.field>

        <x-button wire:click="submit" icon="check" color="green">
            Save Video
        </x-button>
    @endif

</div>