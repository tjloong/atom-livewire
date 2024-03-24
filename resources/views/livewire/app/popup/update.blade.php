<x-form.drawer class="max-w-screen-lg" wire:close="close()">
@if ($popup)
    @if ($popup->exists) 
        <x-slot:heading title="app.label.update-popup"></x-slot:heading>
        <x-slot:buttons delete>
            <x-button.submit sm/>
            <x-button icon="copy" label="app.label.duplicate" sm wire:click="duplicate()"/>
        </x-slot:buttons>
    @else
        <x-slot:heading title="app.label.create-popup"></x-slot:heading>
    @endif

    <x-group cols="2">
        <x-form.text label="app.label.name" wire:model.defer="popup.name"/>
        <x-form.text label="app.label.href" wire:model.defer="popup.href"/>
        <x-form.date time label="app.label.start-date" wire:model.defer="popup.start_at"/>
        <x-form.date time label="app.label.end-date" wire:model.defer="popup.end_at"/>
        <x-form.color full label="app.label.bg-color" wire:model.defer="popup.bg_color"/>
        <x-form.file label="app.label.bg-image" wire:model="popup.image_id" accept="image/*"/>
    </x-group>

    <x-group>
        <x-form.editor label="app.label.content" wire:model.defer="popup.content"/>
    </x-group>
@endif
</x-form.drawer>