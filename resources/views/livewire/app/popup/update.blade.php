<x-form.drawer class="max-w-screen-lg" wire:close="close()">
@if ($popup)
    @if ($popup->exists) 
        <x-slot:buttons delete>
            <x-button action="submit" sm/>
            <x-button action="duplicate" sm/>
        </x-slot:buttons>
        <x-slot:heading title="app.label.update-popup"></x-slot:heading>
    @else
        <x-slot:heading title="app.label.create-popup"></x-slot:heading>
    @endif

    <x-group cols="2">
        <x-form.text label="app.label.name" wire:model.defer="popup.name"/>
        <x-form.text label="app.label.href" wire:model.defer="popup.href"/>
        <x-form.date.datetime label="app.label.start-date" wire:model.defer="popup.start_at"/>
        <x-form.date.datetime label="app.label.end-date" wire:model.defer="popup.end_at"/>
        <x-form.color label="app.label.bg-color" wire:model.defer="popup.bg_color"/>
        <x-form.file label="app.label.bg-image" wire:model="popup.image_id" accept="image/*"/>
    </x-group>

    <x-group>
        <x-editor label="app.label.content" wire:model.defer="popup.content"/>
    </x-group>
@endif
</x-form.drawer>