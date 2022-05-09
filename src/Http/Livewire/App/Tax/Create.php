<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Livewire\Component;

class Create extends Component
{
    public $tax;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->tax = model('tax')->fill(['is_active' => true]);
        
        breadcrumbs()->push('Create Role');
    }

    /**
     * Saved
     */
    public function saved($id)
    {
        session()->flash('flash', __('Tax Created').'::success');
        return redirect()->route('app.tax.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.tax.create');
    }
}