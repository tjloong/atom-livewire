<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;

    public $label;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount($labelId): void
    {
        $this->label = model('label')->readable()->findOrFail($labelId);
        
        breadcrumbs()->push($this->label->locale('name'));
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->label->delete();

        return breadcrumbs()->back();
    }

    /**
     * Submitted
     */
    public function submitted(): mixed
    {
        return $this->popup('Label Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.label.update');
    }
}