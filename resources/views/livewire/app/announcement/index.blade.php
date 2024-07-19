<div x-init="@js($announcementId) && $wire.emit('updateAnnouncement', @js($announcementId))" class="max-w-screen-xl mx-auto">
    <x-heading title="announcement.heading.announcement" xl>
        <x-button icon="add" label="announcement.button.new" wire:click="$emit('createAnnouncement')"/>
    </x-heading>
    @livewire('app.announcement.listing', key('listing'))
</div>