<x-form.drawer wire:close="close()">
    @if ($banner)
        @if ($banner->exists) 
            <x-slot:heading title="{!! $banner->name !!}" :status="$banner->status->badge()"></x-slot:heading>
            <x-slot:buttons delete></x-slot:buttons>
        @else
            <x-slot:heading title="app.label.create-banner"></x-slot:heading>
        @endif
    
        <x-form.group>
            <x-form.select.enum wire:model="banner.type" label="app.label.type" enum="banner.type"/>
            <x-form.text wire:model.defer="banner.name" label="app.label.name"/>
            <x-form.text wire:model.defer="banner.url" label="app.label.link-url"/>
            <x-form.slug wire:model.defer="banner.slug" label="app.label.slug"/>
        </x-form.group>
    
        <x-form.group cols="2">
            <x-form.date wire:model="banner.start_at" label="app.label.start-date"/>
            <x-form.date wire:model="banner.end_at" label="app.label.end-date"/>
            <x-form.text wire:model.defer="banner.description" label="app.label.description"/>
            <x-form.select.enum wire:model="banner.placement" label="app.label.placement" enum="banner.placement" multiple/>
        </x-form.group>
    
        <x-form.group cols="2">
            <x-form.file wire:model="banner.image_id" label="app.label.image" accept="image/*"/>
            <x-form.file wire:model="banner.mob_image_id" label="app.label.image-for-mobile" accept="image/*"/>
        </x-form.group>
    @endif
    </x-form.drawer>