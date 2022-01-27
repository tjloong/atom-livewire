<?php

namespace Jiannius\Atom\Http\Livewire\App\Enquiry;

use Livewire\Component;
use Livewire\WithPagination;
use Jiannius\Atom\Models\Enquiry;

class Listing extends Component
{
    use WithPagination;

    public $search;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';

    protected $queryString = [
        'search', 
        'sortBy' => ['except' => 'created_at'],
        'sortOrder' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.enquiry.listing', [
            'enquiries' => Enquiry::query()
                ->when($this->search, fn($q) => $q->search($this->search))
                ->orderBy($this->sortBy, $this->sortOrder)
                ->paginate(30),
        ]);
    }

    /**
     * Updating search property
     * 
     * @return void
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }
}