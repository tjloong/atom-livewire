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
        $this->tax = model('tax')->when(
            model('tax')->enabledHasTenantTrait,
            fn($q) => $q->belongsToTenant(),
        )->findOrFail($taxId);

        breadcrumbs()->push($this->tax->name);
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