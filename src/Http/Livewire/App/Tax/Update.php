<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Livewire\Component;

class Update extends Component
{
    public $tax;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($tax)
    {
        $this->tax = model('tax')->findOrFail($tax);

        breadcrumbs()->push($this->tax->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->tax->delete();

        session()->flash('flash', __('Tax deleted'));
        
        return redirect()->route('app.tax.listing');
    }

    /**
     * Saved
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => __('Tax Updated'), 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.tax.update');
    }
}