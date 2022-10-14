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
        $this->tax = model('tax')->findOrFail($taxId);

        breadcrumbs()->push($this->tax->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->tax->delete();

        return redirect()->route('app.settings', ['taxes'])->with('info', 'Tax Deleted.');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.tax.update');
    }
}