<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences;

use Livewire\Component;

class Tax extends Component
{
    public $onboarding;

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get taxes property
     */
    public function getTaxesProperty()
    {
        return model('tax')
            ->when(
                model('tax')->enabledHasTenantTrait, 
                fn($q) => $q->belongsToTenant(),
            )
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
        return atom_view('app.preferences.tax');
    }
}