<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences;

use Livewire\Component;

class Taxes extends Component
{
    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get taxes property
     */
    public function getTaxesProperty()
    {
        return model('tax')
            ->when(model('tax')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->orderBy('country')
            ->orderBy('region')
            ->orderBy('name')
            ->get();
    }

    /**
     * Open
     */
    public function open($id = null)
    {
        $this->emitTo(lw('app.settings.system.tax-form-modal'), 'open', $id);
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        optional($this->taxes->firstWhere('id', $id))->delete();

        $this->emit('refresh');
        $this->dispatchBrowserEvent('tax-form-modal-close');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.preferences.taxes');
    }
}