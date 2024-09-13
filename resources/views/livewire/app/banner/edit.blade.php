<x-page wire:close="$emit('closeBanner')" class="max-w-screen-md">
@if ($banner)
    @if ($banner->exists)
        <x-slot:buttons>
            <x-button action="delete" invert/>
        </x-slot:buttons>
    @endif

    <x-form>
        <x-slot:title class="bg-gray-100 border-b"
            :title="$banner->exists ? $banner->name : 'app.label.create-banner'"
            :status="$banner->exists ? [$banner->status->badge()] : null">
        </x-slot:title>

        <x-inputs>
            <x-select wire:model="banner.type" label="app.label.type" options="enum.banner-type" :searchable="false"/>
            <x-input wire:model.defer="banner.name" label="app.label.name"/>
            <x-input wire:model.defer="banner.url" label="app.label.link-url"/>
            <x-input wire:model.defer="banner.slug" label="app.label.slug" prefix="/"/>
            <x-date-picker wire:model="banner.start_at" label="app.label.start-date"/>
            <x-date-picker wire:model="banner.end_at" label="app.label.end-date"/>
            <x-input wire:model.defer="banner.description" label="app.label.description"/>
            <x-select wire:model="banner.placement" label="app.label.placement" options="enum.banner-placement" multiple/>
            <x-file-input wire:model="banner.image_id" label="app.label.image" accept="image/*"/>
            <x-file-input wire:model="banner.mob_image_id" label="app.label.image-for-mobile" accept="image/*"/>
        </x-inputs>
    </x-form>
@endif
</x-page>