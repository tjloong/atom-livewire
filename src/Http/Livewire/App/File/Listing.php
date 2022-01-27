<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Livewire\Component;
use Livewire\WithPagination;
use Jiannius\Atom\Models\File;

class Listing extends Component
{
    use WithPagination;

    public $search;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filterType = 'all';

    protected $queryString = [
        'search' => ['except' => ''], 
        'page' => ['except' => 1],
        'filterType' => ['except' => 'all'],
    ];

    protected $listeners = [
        'saved' => '$refresh',
        'deleted' => '$refresh',
        'uploader-completed' => '$refresh',
    ];

    /**
     * Mount
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.file.listing', [
            'files' => File::query()
                ->when($this->search, fn($q) => $q->search($this->search))
                ->filter([
                    'type' => $this->filterType,
                ])
                ->orderBy($this->sortBy, $this->sortOrder)
                ->paginate(48),
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

    /**
     * Delete file
     * 
     * @return void
     */
    public function delete($id)
    {
        File::whereIn('id', $id)->get()->each(fn($q) => $q->delete());
        
        $this->dispatchBrowserEvent('toast', ['message' => count($id) . ' Files Deleted']);
    }
}