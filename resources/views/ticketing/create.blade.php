<div class="max-w-screen-md mx-auto">
    <x-page-header title="Create Support Ticket" back/>

    <form wire:submit.prevent="submit">
        <x-box>
            <div class="p-5">
                <x-input.text wire:model.defer="ticket.subject" :error="$errors->first('ticket.subject')" required>
                    Subject
                </x-input.text>

                <x-input.textarea wire:model.defer="ticket.description" :error="$errors->first('ticket.description')" required>
                    Issue Description
                </x-input.textarea>
            </div>

            <x-slot name="buttons">
                <x-button type="submit" icon="check" color="green">
                    Save
                </x-button>
            </x-slot>
        </x-box>
    </form>
</div>