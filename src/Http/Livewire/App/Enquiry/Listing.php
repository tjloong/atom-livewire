<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = ['search' => ''];

    protected $queryString = [
        'filters' => ['except' => ['search' => '']], 
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
        return $this->query->paginate(30);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
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
        return view('atom::app.enquiry.listing');
    }
}