<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$ticket->subject" back>
        <x-button.delete inverted
            title="Delete Ticket"
            message="Are you sure to delete this ticket?"
        />
    </x-page-header>

    <div class="flex flex-col gap-6">
        <x-box header="Ticket Information">
            <div class="flex flex-col divide-y">
                <x-field label="Ticket Number" :value="$ticket->number"/>
    
                @if (
                    (enabled_module('permissions') && user()->can('ticket.status'))
                    || (enabled_module('roles') && role('admin'))
                    || (
                        !enabled_module('roles') 
                        && !enabled_module('permissions') 
                        && tier('root')
                    )
                )
                    <x-field label="Status">
                        <div class="w-40 ml-auto">
                            <x-form.select :label="false" wire:model="ticket.status"
                                :options="collect(['pending', 'closed'])->map(fn($val) => ['value' => $val, 'label' => str()->headline($val)])"
                            />
                        </div>
                    </x-field>
                @else
                    <x-field label="Status" :badge="$ticket->status"/>
                @endif

                <div class="p-4">
                    <x-form.field label="Issue Description">
                        {!! nl2br($ticket->description) !!}
                    </x-form.field>
                </div>
            </div>
        </x-box>

        @livewire(atom_lw('app.ticket.comments'), compact('ticket'), key('comments'))
    </div>
</div>