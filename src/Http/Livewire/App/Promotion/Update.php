<?php

namespace Jiannius\Atom\Http\Livewire\App\Promotion;

use Livewire\Component;

class Update extends Component
{
    public $promotion;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount($promotion)
    {
        $this->promotion = model('promotion')
            ->when(model('promotion')->enabledHasTenantTrait, fn($q) => $q->belongsToTenant())
            ->findOrFail($promotion);

        breadcrumbs()->push($this->promotion->name);
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->promotion->delete();

        session()->flash('flash', __('Promotion Deleted'));

        return redirect()->route('app.promotion.listing');
    }

    /**
     * Saved
     */
    public function saved()
    {
        $this->dispatchBrowserEvent('toast', ['message' => __('Promotion Updated'), 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.promotion.update');
    }
}
