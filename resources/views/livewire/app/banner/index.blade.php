<div 
    x-data="{ bannerId: @js($bannerId) }"
    x-init="bannerId && $wire.emit('updateBanner', bannerId)"
    class="max-w-screen-xl mx-auto">
    <x-heading title="atom::banner.heading.banner" 2xl>
        <x-button icon="add"
            label="atom::banner.button.new" 
            wire:click="$emit('createBanner')"/>
    </x-heading>

    @livewire('app.banner.listing', key('listing'))
    @livewire('app.banner.update', key('update'))
</div>