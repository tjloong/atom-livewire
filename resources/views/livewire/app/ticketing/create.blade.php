<div class="max-w-screen-md mx-auto">
    <x-page-header title="Create Support Ticket" back/>

    <x-form>
        <x-form.group>
            <x-form.text wire:model.defer="ticket.subject"/>
            <x-form.textarea label="Issue Description" wire:model.defer="ticket.description"/>
        </x-form.group>
    </x-form>
</div>