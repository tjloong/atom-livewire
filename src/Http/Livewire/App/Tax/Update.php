<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Livewire\Component;

class Update extends Component
{
    public $tax;

    /**
     * Mount
     */
    public function mount($taxId)
    {
        $this->tax = model('tax')->readable()->findOrFail($taxId);

        breadcrumbs()->push($this->tax->label);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->tax->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.tax.update');
    }
}