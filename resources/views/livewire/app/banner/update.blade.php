<x-form.drawer id="banner-update" wire:close="$emit('setBannerId')">
@if ($banner)
    @if ($banner->exists) 
        <x-slot:heading :title="$banner->name"></x-slot:heading>
        <x-slot:buttons delete></x-slot:buttons>
    @else
        <x-slot:heading title="atom::banner.heading.create"></x-slot:heading>
    @endif

    <div class="-m-4">
        <x-form.group>
            <x-form.select label="atom::banner.label.type"
                wire:model="banner.type" 
                :options=" collect(['main-banner'])->map(fn($val) => [
                    'value' => $val,
                    'label' => str()->headline($val),
                ])->toArray()"/>

            <x-form.text label="atom::banner.label.name"
                wire:model.defer="banner.name"/>

            <x-form.text label="atom::banner.label.url"
                wire:model.defer="banner.url" label="Link URL"/>

            <x-form.slug label="atom::banner.label.slug"
                wire:model.defer="banner.slug"/>
        </x-form.group>

        <x-form.group cols="2">
            <x-form.date label="atom::banner.label.start-date"
                wire:model="banner.start_at" label="Start Date"/>
    
            <x-form.date label="atom::banner.label.end-date"
                wire:model="banner.end_at" label="End Date"/>

            <x-form.text label="atom::banner.label.description"
                wire:model.defer="banner.description"/>

            <x-form.select label="atom::banner.label.placement" multiple
                caption="atom::banner.label.leave-empty-placement"
                wire:model="banner.placement" 
                :options="collect(['home'])->map(fn($val) => [
                    'value' => $val,
                    'label' => str()->headline($val),
                ])->toArray()"/>
        </x-form.group>

        <x-form.group>
            <x-form.file label="atom::banner.label.image" accept="image/*"
                wire:model="banner.image_id"/>
        </x-form.group>
    </div>
@endif
</x-form.drawer>