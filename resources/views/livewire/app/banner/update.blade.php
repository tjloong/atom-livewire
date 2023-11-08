<x-form.drawer id="banner-update" wire:close="$emit('setBannerId')">
@if ($banner)
    @if ($banner->exists) 
        <x-slot:heading :title="$banner->name"></x-slot:heading>
        <x-slot:buttons delete></x-slot:buttons>
    @else
        <x-slot:heading title="banner.heading.create"></x-slot:heading>
    @endif

    <div class="-m-4">
        <x-form.group>
            <x-form.select.enum label="banner.label.type" enum="banner.type"
                wire:model="banner.type"/>

            <x-form.text label="banner.label.name"
                wire:model.defer="banner.name"/>

            <x-form.text label="banner.label.url"
                wire:model.defer="banner.url" label="Link URL"/>

            <x-form.slug label="banner.label.slug"
                wire:model.defer="banner.slug"/>
        </x-form.group>

        <x-form.group cols="2">
            <x-form.date label="banner.label.start-date"
                wire:model="banner.start_at" label="Start Date"/>
    
            <x-form.date label="banner.label.end-date"
                wire:model="banner.end_at" label="End Date"/>

            <x-form.text label="banner.label.description"
                wire:model.defer="banner.description"/>

            <x-form.select.enum label="banner.label.placement" enum="banner.placement" multiple
                caption="banner.label.leave-empty-placement"
                wire:model="banner.placement"/>
        </x-form.group>

        <x-form.group>
            <x-form.file label="banner.label.image" accept="image/*" library
                wire:model="banner.image_id"/>
        </x-form.group>
    </div>
@endif
</x-form.drawer>