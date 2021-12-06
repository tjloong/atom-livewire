<?php

namespace App\Http\Livewire\App\BlogCategory;

use App\Models\Label;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $search;
    public $sortBy = 'name';
    public $sortOrder = 'asc';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortOrder' => ['except' => 'asc'],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['saved' => '$refresh'];

    /**
     * Mount event
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
        return view('livewire.app.blog-category.listing', [
            'labels' => Label::query()
                ->withCount('blogs')
                ->when($this->search, fn($q) => $q->search($this->search))
                ->where('type', 'blog-category')
                ->orderBy($this->sortBy, $this->sortOrder)
                ->paginate(30),
        ]);
    }
}