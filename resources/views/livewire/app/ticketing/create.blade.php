<div class="max-w-screen-md mx-auto">
    <x-page-header title="Create Support Ticket" back/>

    <x-form>
        <x-form.text
            label="Subject"
            wire:model.defer="ticket.subject"
            :error="$errors->first('ticket.subject')" 
            required
        />

        <x-form.textarea
            label="Issue Description"
            wire:model.defer="ticket.description"
            :error="$errors->first('ticket.description')"
            required
        />

        <x-slot:foot>
            <x-button.submit/>
        </x-slot:foot>
    </x-form>
</div>