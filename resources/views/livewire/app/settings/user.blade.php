<div class="lg:max-w-screen-sm">
    <div class="flex items-center justify-between mb-4">
        <atom:_heading size="lg">@t('user')</atom:_heading>
        <atom:_button icon="add" x-on:click="Atom.modal('edit-user').show()">@t('new-user')</atom:_button>
    </div>

    <livewire:app.user.listing wire:key="listing"/>
    <livewire:app.user.edit wire:key="edit"/>
</div>