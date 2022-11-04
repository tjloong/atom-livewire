@if ($slot->isEmpty())
    <div
        x-data
        x-tooltip="Export"
        wire:click.prevent="export"
        class="cursor-pointer p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200"
    >
        <x-icon name="export" class="m-auto"/>
    </div>
@else
    <x-dropdown>
        <x-slot:anchor>
            <div class="p-2 rounded-full flex text-gray-500 hover:text-gray-800 hover:bg-gray-200">
                <x-icon name="export" class="m-auto"/>
            </div>
        </x-slot:anchor>

        {{ $slot }}
    </x-dropdown>
@endif
