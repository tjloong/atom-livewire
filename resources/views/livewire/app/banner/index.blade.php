<div x-init="@js($bannerId) && $wire.emit('updateBanner', @js($bannerId))" class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.banner:2" 2xl>
        <x-button icon="add" label="app.label.new-banner" wire:click="$emit('createBanner')"/>
    </x-heading>
    @livewire('app.banner.listing', key('listing'))
</div>