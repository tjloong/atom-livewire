<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Create extends Component
{
    use WithPopupNotify;

    public $label;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->label = model('label')->fill([
            'type' => request()->query('type'),
            'parent_id' => request()->query('parent_id'),
        ]);
        
        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return 'Create '.str($this->label->type ?? 'Label')->headline()->toString();
    }

    /**
     * Submitted
     */
    public function submitted(): mixed
    {
        return breadcrumbs()->back();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.label.create');
    }
}