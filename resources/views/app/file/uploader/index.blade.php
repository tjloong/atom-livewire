<div 
    x-data 
    x-on:{{ $uid }}-open.window="$wire.set('open', true)"
    x-on:{{ $uid }}-close.window="$wire.set('open', false)"
>
    @if ($open)
        <div x-data class="modal">
            <div class="modal-bg"></div>
            <div class="modal-container" x-on:click="$wire.set('open', false)">
                <div class="modal-content max-w-screen-sm p-5" x-on:click.stop>
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-lg font-bold">
                            {{ $title }}
                        </div>
        
                        <a wire:click="$set('open', false)" class="flex items-center justify-center text-gray-800">
                            <x-icon name="xmark"/>
                        </a>
                    </div>
        
                    <div class="flex flex-wrap items-center space-x-6 mb-4 -mt-2">
                        @foreach ($this->tabs as $item)
                            <a 
                                class="py-1.5 {{ $tab === $item['name']
                                    ? 'text-theme font-semibold border-b-2 border-theme'
                                    : 'text-gray-400 font-medium hover:text-gray-600'
                                }}"
                                wire:click="$set('tab', '{{ $item['name'] }}')"
                            >
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>

                    @livewire('atom.app.file.uploader.'.$tab, [
                        'accept' => $accept,
                        'private' => $private,
                        'multiple' => $multiple,
                        'inputFileTypes' => $this->inputFileTypes,
                    ], key($tab))
                </div>
            </div>
        </div>
    @endif
</div>

