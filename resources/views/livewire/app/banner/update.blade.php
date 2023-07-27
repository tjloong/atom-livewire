<x-form id="banner-update" :header="optional($banner)->exists ? 'Update Banner' : 'Create Banner'" drawer>
@if ($banner)
    <x-slot:buttons>
        <x-button.submit size="sm"/>
        @if ($banner->exists)
            <x-button.delete size="sm" :label="false" inverted
                title="Delete Banner"
                message="Are you sure to DELETE this banner?"
            />
        @endif
    </x-slot:buttons>

    <x-form.group>
        <x-form.select wire:model="banner.type" :options="data_get($this->options, 'types')"/>
        <x-form.text wire:model.defer="banner.name"/>
        <x-form.text wire:model.defer="banner.url" label="Link URL"/>
        <x-form.slug wire:model.defer="banner.slug"/>
        <x-form.date wire:model="banner.start_at" label="Start Date"/>
        <x-form.date wire:model="banner.end_at" label="End Date"/>
        <x-form.textarea wire:model.defer="banner.description"/>
            
        <x-form.select wire:model="banner.placement" :options="data_get($this->options, 'placements')" multiple
            caption="Leave empty to place in all pages"
        />
            
        <x-form.file wire:model="banner.image_id" accept="image/*"/>
    </x-form.group>
@endif
</x-form>