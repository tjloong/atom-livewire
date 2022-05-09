<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Dashboard">
        <x-dropdown right>
            <x-slot:trigger>
                <a class="flex items-center bg-gray-200 py-2 px-3 rounded-md space-x-2 text-gray-800">
                    <x-icon name="calendar" class="text-gray-500"/>
        
                    <div class="flex-grow flex items-center space-x-2">
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
            </x-slot:trigger>

            <div class="grid gap-6 p-5">
                <x-form.date 
                    label="From"
                    wire:model.defer="dateFrom"
                />
                <x-form.date 
                    label="To"
                    wire:model.defer="dateTo"
                />

                <x-button label="Apply" color="green" wire:click="$refresh"/>
            </div>
        </x-dropdown>
    </x-page-header>

    <div class="grid gap-6 md:grid-cols-4">
        @if ($blogs)
            <x-stat-box title="Total Articles">
                {{ $blogs['count'] }}
            </x-stat-box>

            <x-stat-box title="Total Published">
                {{ $blogs['published'] }}
            </x-stat-box>
        @endif

        @if ($enquiries)
            <x-stat-box title="Total Enquiries">
                {{ $enquiries['count'] }}
            </x-stat-box>

            <x-stat-box title="Pending Enquiries">
                {{ $enquiries['pending'] }}
            </x-stat-box>
        @endif
    </div>
</div>