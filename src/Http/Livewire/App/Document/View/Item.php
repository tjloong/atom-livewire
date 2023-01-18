<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Livewire\Component;

class Item extends Component
{
    public $document;
    public $columns;

    /**
     * Mount
     */
    public function mount()
    {
        $this->columns = $this->document->getColumns();
    }

    /**
     * Get items property
     */
    public function getItemsProperty()
    {
        if ($master = $this->document->splittedFrom) return $master->items;
        
        return $this->document->items;
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view.item');
    }
}