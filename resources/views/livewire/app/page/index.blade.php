<div x-init="@js($pageId) && $wire.emit('updatePage', @js($pageId))" class="max-w-screen-xl mx-auto">
    <x-heading title="app.label.page:2"/>
    @livewire('app.page.listing', key('listing'))
</div>
