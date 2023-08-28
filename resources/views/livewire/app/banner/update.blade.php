<x-form.drawer id="banner-update" wire:close="$emit('bannerSaved')">
@if ($banner)
    <x-slot:heading :title="$banner->exists ? $banner->name : 'Create Banner'"></x-slot:heading>
    @if ($banner->exists) <x-slot:buttons delete></x-slot:buttons> @endif

    <x-form.group class="p-0">
        <x-form.select wire:model="banner.type" :options="data_get($this->options, 'types')"/>
        <x-form.text wire:model.defer="banner.name"/>
        <x-form.text wire:model.defer="banner.url" label="Link URL"/>
        <x-form.slug wire:model.defer="banner.slug"/>
        <x-form.date wire:model="banner.start_at" label="Start Date"/>
        <x-form.date wire:model="banner.end_at" label="End Date"/>
        <x-form.textarea wire:model.defer="banner.description"/>
            
        <x-form.select wire:model="banner.placement" :options="data_get($this->options, 'placements')" multiple
            caption="Leave empty to place in all pages"/>
            
        <x-form.file wire:model="banner.image_id" accept="image/*"/>
    </x-form.group>
@endif
</x-form.drawer>