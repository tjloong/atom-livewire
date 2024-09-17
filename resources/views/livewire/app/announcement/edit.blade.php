<x-page submit wire:close="$emit('closeAnnouncement')" class="max-w-screen-md">
@if ($announcement)
    @if ($announcement->exists)
        <x-slot:buttons>
            <x-button action="duplicate"/>
            <x-button action="delete" no-label invert/>
        </x-slot:buttons>
    @endif

    <x-form>
        <x-slot:title
            :title="$announcement->exists ? 'app.label.edit-announcement' : 'app.label.create-announcement'"
            class="bg-gray-100 border-b">
        </x-slot:title>

        <div class="flex flex-col divide-y">
            <x-inputs>
                <x-input label="app.label.title" wire:model.defer="announcement.name"/>
                <x-input label="app.label.slug" wire:model.defer="announcement.slug"/>
                <x-datetime-picker label="app.label.start-date" wire:model.defer="announcement.start_at"/>
                <x-datetime-picker label="app.label.end-date" wire:model.defer="announcement.end_at"/>
                <x-color-picker label="app.label.bg-color" wire:model.defer="announcement.bg_color"/>
                <x-color-picker label="app.label.text-color" wire:model.defer="announcement.text_color"/>
                <x-input label="app.label.href" wire:model.defer="announcement.href"/>
                <x-editor label="app.label.content" wire:model.defer="announcement.content"/>
            </x-inputs>

            <x-seo-input wire:model.defer="announcement.seo"/>
        </div>
    </x-form>
@endif
</x-page>