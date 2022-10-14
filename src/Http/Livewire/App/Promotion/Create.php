<?php

namespace Jiannius\Atom\Http\Livewire\App\Promotion;

use Livewire\Component;

class Create extends Component
{
    public $promotion;

    protected $listeners = ['saved'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->promotion = model('promotion')->fill([
            'is_active' => true,
        ]);

        breadcrumbs()->push('Create Promotion');
    }

    /**
     * Saved
     */
    public function saved($id)
    {
        return redirect()->route('app.promotion.listing');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.promotion.create');
    }
}
