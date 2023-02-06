<?php

namespace Jiannius\Atom\Http\Livewire\App\Ticketing;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $fullpage;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = [
        'search' => null,
        'status' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'search' => null,
            'status' => null,
        ]],
        'page' => ['except' => 1],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        if ($this->fullpage = current_route('app.ticketing.listing')) {
            breadcrumbs()->home('Support Tickets');
        }
    }

    /**
     * Get tickets property
     */
    public function getTicketsProperty()
    {
        return model('ticket')
            ->selectRaw('tickets.*, if (tickets.status = "new", 0, 1) as seq')
            ->when(
                !auth()->user()->is_root, 
                fn($q) => $q->where('created_by', auth()->user()->id)
            )
            ->filter($this->filters)
            ->orderBy('seq')
            ->orderBy($this->sortBy, $this->sortOrder)
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
                    'href' => route('app.ticketing.update', [$ticket->id]),
                ],
                [
                    'column_name' => 'Subject',
                    'column_sort' => 'subject',
                    'label' => str($ticket->subject)->limit(50),
                    'small' => str($ticket->description)->limit(50),
                    'href' => route('app.ticketing.update', [$ticket->id]),
                ],
                [
                    'tags' => collect([model('ticket_comment')->getUnreadCount($ticket->id)])
                        ->filter(fn($n) => $n > 0)
                        ->values()
                        ->all(),
                ],
                [
                    'column_name' => 'Status',
                    'column_sort' => 'status',
                    'status' => $ticket->status,
                ],
                auth()->user()->is_root ? [
                    'column_name' => 'Created By',
                    'label' => str(optional($ticket->createdBy)->name)->limit(15),
                ] : null,
            ]));
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.ticketing.listing');
    }
}