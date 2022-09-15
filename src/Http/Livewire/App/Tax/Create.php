<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Livewire\Component;

class Create extends Component
{
    public $tax;

    /**
     * Mount
     */
    public function mount()
    {
        $this->tax = model('tax')->fill(['is_active' => true]);
        
        breadcrumbs()->push('Create Tax');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.tax.create');
    }
}