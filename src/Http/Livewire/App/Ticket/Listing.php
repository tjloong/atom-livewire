<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticket;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $fullpage;
    public $sort;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    // mount
    public function mount()
    {
        $this->fullpage = current_route('app.ticket.listing');
    }

    // get tickets property
    public function getTicketsProperty()
    {
        return model('ticket')
            ->selectRaw('tickets.*, if (tickets.status = "new", 0, 1) as seq')
            ->when(!tier('root'), fn($q) => $q->where('created_by', user('id')))
            ->withCount(['comments' => fn($q) => $q->unread()])
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest())
            ->orderBy('seq')
            ->paginate($this->maxRows)
            ->through(fn($ticket) => array_filter([
                [
                    'column_name' => 'Date',
                    'column_sort' => 'created_at',
                    'datetime' => $ticket->created_at,
                ],
                [
                    'column_name' => 'Number',
                    'column_sort' => 'number',
                    'label' => $ticket->number,
                    'href' => route('app.ticket.update', [$ticket->id]),
                ],
                [
                    'column_name' => 'Subject',
                    'column_sort' => 'subject',
                    'label' => str($ticket->subject)->limit(50),
                    'small' => str($ticket->description)->limit(50),
                    'href' => route('app.ticket.update', [$ticket->id]),
                ],
                [
                    'tags' => collect([$ticket->comments_count])
                        ->filter(fn($n) => $n > 0)
                        ->values()
                        ->all(),
                ],
                [
                    'column_name' => 'Status',
                    'column_sort' => 'status',
                    'status' => $ticket->status,
                ],
                tier('root') ? [
                    'column_name' => 'Created By',
                    'label' => str(optional($ticket->createdBy)->name)->limit(15),
                ] : null,
            ]));
    }

    // render
    public function render()
    {
        return atom_view('app.ticket.listing');
    }
}