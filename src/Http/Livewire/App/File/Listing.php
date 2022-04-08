<?php

namespace Jiannius\Atom\Http\Livewire\App\File;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $open = false;
    public $selected = [];
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
        'delete',
        'saved' => '$refresh',
        'uploader-completed' => '$refresh',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->flush();
    }

    /**
     * Get files property
     */
    public function getFilesProperty()
    {
        return model('file')
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate(48);
    }

    /**
     * Updated filters
     */
    public function updatedFilters()
    {
        $this->resetPage();
    }

    /**
     * Updated open
     */
    public function updatedOpen()
    {
        $this->toggleDrawer();
    }

    /**
     * Toggle drawer
     */
    public function toggleDrawer()
    {
        if ($this->open) $this->dispatchBrowserEvent('drawer-open');
        else $this->dispatchBrowserEvent('drawer-close');        
    }

    /**
     * Select
     */
    public function select($id)
    {
        if ($id === 'all') {
            $this->selected = collect($this->files->items())->pluck('id')->toArray();
        }
        else if (in_array($id, $this->selected)) {
            $this->selected = collect($this->selected)->reject(fn($val) => $val === $id)->values()->all();
        }
        else {
            array_push($this->selected, $id);
        }
    }

    /**
     * Delete file
     */
    public function delete($id = null)
    {
        if ($id) {
            optional(model('file')->find($id))->delete();
            $this->dispatchBrowserEvent('toast', ['message' => 'File Deleted']);
        }
        else {
            model('file')->whereIn('id', $this->selected)->get()->each(fn($q) => $q->delete());
            $this->dispatchBrowserEvent('toast', ['message' => count($this->selected).' Files Deleted']);
        }
        
        $this->open = false;
        $this->toggleDrawer();
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.file.listing');
    }
}