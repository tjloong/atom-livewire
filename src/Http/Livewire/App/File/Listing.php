<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $openedFile;
    public $sortBy = 'created_at';
    public $sortOrder = 'desc';
    public $filters = [
        'type' => 'all',
        'search' => '',
    ];

    protected $queryString = [
        'filters',
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'saved' => '$refresh',
        'deleted' => '$refresh',
        'uploader-completed' => '$refresh',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Get files property
     */
    public function getFilesProperty()
    {
        return model('file')->filter($this->filters)->orderBy($this->sortBy, $this->sortOrder);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
    }

    /**
     * Open file
     */
    public function openFile($id)
    {
        $this->openedFile = model('file')->find($id);
        $this->dispatchBrowserEvent('drawer-open');
    }

    /**
     * Close file
     */
    public function closeFile()
    {
        $this->openedFile = null;
        $this->dispatchBrowserEvent('drawer-close');
    }

    /**
     * Delete file
     */
    public function delete($id)
    {
        model('file')->whereIn('id', $id)->get()->each(fn($q) => $q->delete());
        
        $this->dispatchBrowserEvent('toast', ['message' => count($id) . ' Files Deleted']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.file.listing', [
            'files' => $this->files->paginate(48),
        ]);
    }
}