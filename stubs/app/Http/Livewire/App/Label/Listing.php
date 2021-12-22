<?php

namespace App\Http\Livewire\App\Label;

use App\Models\Label;
use Livewire\Component;

class Listing extends Component
{
    public $tab;
    public $types;
    public $labels;

    protected $queryString = [
        'tab' => ['except' => ''],
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->types = Label::getTypes();
        $this->tab = request()->query('tab') ?? array_key_first($this->types);

        $this->getLabels();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.label.listing');
    }

    /**
     * Updated tab
     * 
     * @return void
     */
    public function updatedTab()
    {
        $this->getLabels();
    }

    /**
     * Get labels
     * 
     * @return array
     */
    public function getLabels()
    {
        $this->labels = Label::query()
            ->where('type', $this->tab)
            ->orderBy('name')
            ->get()
            ->toArray();
    }
}