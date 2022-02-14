<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Livewire\Component;
use Livewire\WithPagination;
use Jiannius\Atom\Models\Enquiry;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = ['search' => ''];

    protected $queryString = [
        'filters', 
        'sortBy' => ['except' => 'created_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    /**
     * Get enquiries property
     */
    public function getEnquiriesProperty()
    {
        return Enquiry::filter($this->filters)->orderBy($this->sortBy, $this->sortOrder);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.enquiry.listing', [
            'enquiries' => $this->enquiries->paginate(30),
        ]);
    }

    /**
     * Export
     */
    public function export()
    {
        $filename = 'enquiries-' . rand(1000, 9999) . '.xlsx';
        $enquiries = $this->enquiries->get();

        return export_to_excel($filename, $enquiries, fn($enquiry) => [
            'Date' => $enquiry->created_at->toDatetimeString(),
            'Name' => $enquiry->name,
            'Phone' => $enquiry->phone,
            'Email' => $enquiry->email,
            'Message' => $enquiry->message,
            'Status' => $enquiry->status,
        ]);
    }
}