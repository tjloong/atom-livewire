<div class="max-w-screen-md mx-auto">
    <x-page-header :title="$ticket->subject" back>
        <x-button icon="trash" color="red" inverted x-on:click="$dispatch('confirm', {
            title: 'Delete Ticket',
            message: 'Are you sure to delete this ticket?',
            type: 'error',
            onConfirmed: () => $wire.delete(),
        })">
            Delete
        </x-button>
    </x-page-header>

    <x-box>
        <div class="p-5">
            <x-input.field>
                <x-slot name="label">Ticket Number</x-slot>
                {{ $ticket->number }}
            </x-input.field>

            <x-input.field>
                <x-slot name="label">Issue Description</x-slot>
                {!! nl2br($ticket->description) !!}
            </x-input.field>

            @if (auth()->user()->isRole(['admin', 'root']))
                <x-input.field>
                    <x-slot name="label">Created By</x-slot>
                    {{ $ticket->creator->name }}
                </x-input.field>

                <x-input.select
                    wire:model="ticket.status"
                    :options="collect($statuses)->map(fn($value) => ['value' => $value, 'label' => Str::headline($value)])"
                >
                    Status
                </x-input.select>
            @else
                <x-input.field>
                    <x-slot name="label">Status</x-slot>
                    <x-badge>{{ $ticket->status }}</x-badge>
                </x-input.field>
            @endif
        </div>
    </x-box>

    @livewire('atom.ticket.comments', ['ticket' => $ticket], key('ticket-comments'))
</div>