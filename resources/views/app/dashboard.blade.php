<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Dashboard">
        <x-dropdown right>
            <x-slot name="trigger">
                <a class="flex items-center bg-gray-200 py-2 px-3 rounded-md space-x-2 text-gray-800">
                    <x-icon name="calendar" size="18px" class="text-gray-500"/>
        
                    <div class="flex-grow flex items-center text-sm space-x-2">
                        @if ($dateFrom && $dateTo)
                            <span>{{ format_date($dateFrom) }}</span>
                            <x-icon name="right-arrow-alt"/>
                            <span>{{ format_date($dateTo) }}</span>
                        @elseif ($dateFrom && !$dateTo)
                            Since {{ format_date($dateFrom) }}
                        @else
                            No date
                        @endif
                    </div>
        
                    <x-icon name="chevron-down"/>
                </a>
            </x-slot>

            <div class="md:w-[350px]">
                <div class="p-5">
                    <x-input.date wire:model.defer="dateFrom">
                        From
                    </x-input.date>
    
                    <x-input.date wire:model.defer="dateTo">
                        To
                    </x-input.date>
                </div>

                <div class="bg-gray-100 p-4">
                    <x-button class="w-full" color="green" wire:click="$refresh" x-on:click="$dispatch('close')">
                        Apply
                    </x-button>
                </div>
            </div>
        </x-dropdown>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-4">
        <x-stat-box title="Total Articles">
            {{ $sum['blogs'] }}
        </x-stat-box>

        <x-stat-box title="Total Published">
            {{ $sum['published'] }}
        </x-stat-box>

        <x-stat-box title="Total Enquiries">
            {{ $sum['enquiries'] }}
        </x-stat-box>

        <x-stat-box title="Pending Enquiries">
            {{ $sum['pending'] }}
        </x-stat-box>
    </div>
</div>