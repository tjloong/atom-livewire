<div class="max-w-screen-xl mx-auto"
    x-init="$wire.get('bannerId') && $wire.emit('updateBanner', $wire.get('bannerId'))">
    <x-heading title="banner.heading.banner" 2xl>
        <x-button icon="add" label="banner.button.new" 
            wire:click="$emit('createBanner')"/>
    </x-heading>
    @livewire('app.banner.listing', key('listing'))
</div>