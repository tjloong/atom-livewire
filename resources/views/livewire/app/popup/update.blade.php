<x-form.drawer id="popup-update" class="max-w-screen-lg p-5" wire:close="close()">
@if ($popup)
    @if ($popup->exists) 
        <x-slot:heading title="popup.heading.update"></x-slot:heading>
        <x-slot:buttons delete>
            <x-button.submit sm/>
            <x-button icon="copy" label="common.label.duplicate" sm wire:click="duplicate()"/>
        </x-slot:buttons>
    @else
        <x-slot:heading title="popup.heading.create"></x-slot:heading>
    @endif

    <div class="-m-4">
        <x-form.group cols="2">
            <x-form.text label="common.label.name" wire:model.defer="popup.name"/>
            <x-form.text label="common.label.href" wire:model.defer="popup.href"/>
            <x-form.date time label="common.label.start-date" wire:model.defer="popup.start_at"/>
            <x-form.date time label="common.label.end-date" wire:model.defer="popup.end_at"/>
            <x-form.color full label="common.label.bg-color" wire:model.defer="popup.bg_color"/>
            <x-form.file label="common.label.bg-image" wire:model="popup.image_id" accept="image/*"/>
        </x-form.group>

        <x-form.group>
            <x-form.editor label="common.label.content" wire:model.defer="popup.content"/>
        </x-form.group>
    </div>
@endif
</x-form.drawer>