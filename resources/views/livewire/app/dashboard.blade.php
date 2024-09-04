<div class="max-w-screen-xl mx-auto">
    <x-heading title="Dashboard" xl/>

    <div class="bg-gray-100 rounded-lg border p-4 flex flex-col gap-4">
        <div class="font-medium">
            {{ tr('app.label.filter') }}
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <x-date-picker mode="range" wire:model="filters.date" no-label/>
        </div>
    </div>
</div>