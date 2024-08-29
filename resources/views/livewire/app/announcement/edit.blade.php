<x-drawer submit wire:close="$emit('closeAnnouncement')" class="max-w-screen-lg">
@if ($announcement)
    @if ($announcement->exists) <x-slot:heading title="app.label.update-announcement"></x-slot:heading>
    @else <x-slot:heading title="app.label.create-announcement"></x-slot:heading>
    @endif

    <x-slot:buttons>
        <x-button action="submit"/>
        @if ($announcement->exists)
            <x-button action="duplicate"/>
            <x-button action="delete" no-label invert/>
        @endif
    </x-slot:buttons>

    <x-inputs cols>
        <x-input label="app.label.title" wire:model.defer="announcement.name"/>
        <x-input label="app.label.slug" wire:model.defer="announcement.slug"/>
        <x-datetime-picker label="app.label.start-date" wire:model.defer="announcement.start_at"/>
        <x-datetime-picker label="app.label.end-date" wire:model.defer="announcement.end_at"/>
        <x-color label="app.label.bg-color" wire:model.defer="announcement.bg_color"/>
        <x-color label="app.label.text-color" wire:model.defer="announcement.text_color"/>
    </x-inputs>

    <x-inputs>
        <x-input label="app.label.href" wire:model.defer="announcement.href"/>
        <x-editor label="app.label.content" wire:model.defer="announcement.content"/>
    </x-inputs>

    <x-inputs title="SEO">
        <x-form.seo wire:model.defer="inputs.seo"/>
    </x-inputs>
@endif
</x-drawer>