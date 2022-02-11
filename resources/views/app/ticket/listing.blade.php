<div class="max-w-screen-xl mx-auto">
    <x-page-header title="Support Tickets">
        <x-button icon="plus" href="{{ route('ticket.create') }}">
            New Ticket
        </x-button>
    </x-page-header>

    <x-table :total="$tickets->total()" :links="$tickets->links()">
        <x-slot name="head">
            <x-table head sort="created_at">Date</x-table>
            <x-table head sort="number">Number</x-table>
            <x-table head sort="subject">Subject</x-table>
            <x-table head align="right">Status</x-table>
            <x-table head align="right">Created By</x-table>
        </x-slot>

        <x-slot name="body">
        @foreach ($tickets as $ticket)
            <x-table row>
                <x-table cell>
                    {{ format_date($ticket->created_at) }}
                    <div class="text-xs text-gray-400">
                        {{ format_date($ticket->created_at, 'time') }}
                    </div>
                </x-table>
                <x-table cell>
                    <a href="{{ route('ticket.update', [$ticket]) }}">
                        {{ $ticket->number }}
                    </a>
                </x-table>
                <x-table cell>
                    <a href="{{ route('ticket.update', [$ticket]) }}">
                        {{ Str::limit($ticket->subject, 50) }}
                    </a>
                    <div class="text-xs text-gray-400">
                        {{ Str::limit($ticket->description, 80) }}
                    </div>
                </x-table>
                <x-table cell class="text-right">
                    <x-badge>{{ $ticket->status }}</x-badge>
                </x-table>
                <x-table cell class="text-right">
                    {{ Str::limit($ticket->creator->name ?? '--', 20) }}
                </x-table>
            </x-table>
        @endforeach
        </x-slot>
    </x-table>
</div>