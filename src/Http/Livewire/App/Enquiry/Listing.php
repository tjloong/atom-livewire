<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;
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
        return $this->query->paginate($this->maxRows);
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