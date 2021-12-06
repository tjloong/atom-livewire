<div class="flex flex-col space-y-4">
    <div class="relative shadow rounded-lg border-b border-gray-200 w-full bg-white overflow-hidden">
        <div class="absolute inset-0 bg-white opacity-75" wire:loading.delay.long></div>
    
        <div class="rounded-t-lg">
            <div class="py-1 px-4 flex flex-wrap justify-between items-center">
                <div class="my-1">
                @if ($attributes->get('total'))
                    <div class="text-gray-800">
                        Total <span class="font-semibold">{{ $attributes->get('total') }}</span> record(s)
                    </div>
                @endif
                </div>
    
                <div class="flex flex-wrap items-center space-x-2 my-1">
                @isset($checked)
                    {{ $checked }}
                @else
                    <x-input.search/>
    
                    @if ($showExport)
                        <a
                            tooltip="Export"
                            class="text-lg p-1.5 m-1 rounded flex items-center justify-center text-gray-900 hover:bg-gray-100"
                        >
                            <x-icon name="download" size="18px" />
                        </a>
                    @endif
                        
                    @if ($showFilters)
                        <a
                            tooltip="Filters"
                            class="text-lg p-1.5 m-1 rounded flex items-center justify-center text-gray-900 hover:bg-gray-100"
                        >
                            <x-icon name="slider" size="18px" />
                        </a>
                    @endif
                @endisset
                </div>
            </div>
        </div>

        @isset($toolbar)
            <div class="border-t py-2 px-4">
                {{ $toolbar }}
            </div>
        @endisset
    
        @if ($attributes->get('total'))
            <div class="w-full overflow-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        {{ $head }}
                    </thead>
                
                    <tbody>
                        {{ $body }}
                    </tbody>
                </table>
            </div>
        @else
            @isset($empty)
                {{ $empty }}
            @else
                <x-empty-state/>
            @endisset
        @endif
    </div>
    
    {!! $attributes->get('links') !!}
</div>