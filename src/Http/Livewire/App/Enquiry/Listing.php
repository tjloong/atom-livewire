<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = ['search' => null];

    protected $queryString = [
        'filters' => ['except' => ['search' => null]], 
        'sortBy' => ['except' => 'created_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Enquiries');
    }

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('enquiry')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder);
    }

    /**
     * Get enquiries property
     */
    public function getEnquiriesProperty()
    {
        return $this->query->paginate($this->maxRows)->through(fn($enquiry) => [
            [
                'column_name' => 'Date',
                'column_sort' => 'created_at',
                'date' => $enquiry->created_at,
            ],
            [
                'column_name' => 'Name',
                'column_sort' => 'name',
                'label' => $enquiry->name,
                'href' => route('app.enquiry.update', [$enquiry->id]),
            ],
            [
                'column_name' => 'Phone',
                'label' => $enquiry->phone,
            ],
            [
                'column_name' => 'Email',
                'label' => $enquiry->email,
            ],
            [
                'column_name' => 'Status',
                'status' => $enquiry->status,
            ],
        ]);
    }

    /**
     * Export
     */
    public function export()
    {
        return excel(
            $this->query->get(), 
            ['filename' => 'enquiries-'.time()],
            fn($enquiry) => [
                'Date' => $enquiry->created_at->toDatetimeString(),
                'Name' => $enquiry->name,
                'Phone' => $enquiry->phone,
                'Email' => $enquiry->email,
                'Message' => $enquiry->message,
                'Status' => $enquiry->status,
            ]
        );
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.enquiry.listing');
    }
}