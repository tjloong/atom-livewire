<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$ticket->subject" back>
        <x-button.delete inverted
            title="Delete Ticket"
            message="Are you sure to delete this ticket?"
        />
    </x-page-header>

    <x-box>
        <div class="p-5">
            <x-form.field label="Ticket Number">
                {{ $ticket->number }}
            </x-form.field>

            <x-form.field label="Issue Description">
                {!! nl2br($ticket->description) !!}
            </x-form.field>

            @if (
                (enabled_module('permissions') && auth()->user()->can('ticketing.status'))
                || (enabled_module('roles') && auth()->user()->isRole('admin'))
                || (
                    !enabled_module('roles') 
                    && !enabled_module('permissions') 
                    && auth()->user()->isAccountType(['root', 'system'])
                )
            )
                <x-form.select
                    label="Status"
                    wire:model="ticket.status"
                    :options="collect(['pending', 'closed'])->map(fn($val) => ['value' => $val, 'label' => str()->headline($val)])"
                />
            @else
                <x-form.field label="Status">
                    <x-badge :label="$ticket->status"/>
                </x-form.field>
            @endif
        </div>
    </x-box>

    @if ($component = livewire_name('ticketing/comments'))
        @livewire($component, compact('ticket'), key('comments'))
    @endif
</div>