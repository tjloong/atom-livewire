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
                <x-box.row label="Ticket Number">
                    {{ $ticket->number }}
                </x-box.row>
    
                <x-box.row label="Issue Description">
                    {!! nl2br($ticket->description) !!}
                </x-box.row>
    
                <x-box.row label="Status">
                    @if (
                        (enabled_module('permissions') && auth()->user()->can('ticketing.status'))
                        || (enabled_module('roles') && auth()->user()->isRole('admin'))
                        || (
                            !enabled_module('roles') 
                            && !enabled_module('permissions') 
                            && auth()->user()->is_root
                        )
                    )
                        <x-dropdown>
                            <x-slot:anchor>
                                <x-badge :label="$ticket->status"/>
                                <x-icon name="chevron-down" size="12"/>
                            </x-slot:anchor>

                            @foreach (['pending', 'closed'] as $item)
                                <x-dropdown.item 
                                    :label="ucfirst($item)"
                                    wire:click="$set('ticket.status', '{{ $item }}')"
                                />
                            @endforeach
                        </x-dropdown>
                    @else
                        <x-badge :label="$ticket->status"/>
                    @endif
                </x-box.row>
            </div>
        </x-box>

        @livewire(lw('app.ticketing.comments'), compact('ticket'), key('comments'))
    </div>
</div>