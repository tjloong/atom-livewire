<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Livewire\Component;

class Update extends Component
{
    public $tax;

    /**
     * Mount
     */
    public function mount($taxId): void
    {
        $this->tax = model('tax')->readable()->findOrFail($taxId);

        breadcrumbs()->push($this->tax->label);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->tax->delete();

        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.tax.update');
    }
}