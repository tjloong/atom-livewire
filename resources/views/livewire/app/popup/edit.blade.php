<x-drawer submit wire:close="$emit('closePopup')" class="max-w-screen-lg">
@if ($popup)
    @if ($popup->exists) <x-slot:heading title="app.label.update-popup"></x-slot:heading>
    @else <x-slot:heading title="app.label.create-popup"></x-slot:heading>
    @endif

    <x-slot:buttons>
        <x-button action="submit"/>
        @if ($popup->exists)
            <x-button action="duplicate"/>
            <x-button action="delete" no-label invert/>
        @endif
    </x-slot:buttons>

    <x-inputs cols>
        <x-input label="app.label.name" wire:model.defer="popup.name"/>
        <x-input label="app.label.href" wire:model.defer="popup.href"/>
        <x-datetime-picker label="app.label.start-date" wire:model.defer="popup.start_at"/>
        <x-datetime-picker label="app.label.end-date" wire:model.defer="popup.end_at"/>
        <x-color label="app.label.bg-color" wire:model.defer="popup.bg_color"/>
        <x-file-input label="app.label.bg-image" wire:model="popup.image_id" accept="image/*"/>
    </x-inputs>

    <x-inputs>
        <x-editor label="app.label.content" wire:model.defer="popup.content"/>
    </x-inputs>
@endif
</x-drawer>