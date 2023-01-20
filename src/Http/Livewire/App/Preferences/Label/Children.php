<?php

namespace Jiannius\Atom\Http\Livewire\App\Preferences\Label;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Children extends Component
{
    use WithPopupNotify;

    public $parent;
    public $depth = 1;
    public $maxDepth = 1;

    protected $listeners = ['refresh' => '$refresh'];

    /**
     * Get children property
     */
    public function getChildrenProperty()
    {
        return $this->parent->children()
            ->with('children')
            ->oldest('seq')
            ->oldest('id')
            ->get();
    }

    /**
     * Get padding property
     */
    public function getPaddingProperty()
    {
        return $this->depth * 1.5;
    }

    /**
     * Sort
     */
    public function sort($data)
    {
        foreach ($data as $seq => $id) {
            model('label')->find($id)->fill(['seq' => $seq])->saveQuietly();
        }
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        $this->emitUp('delete', $id);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.preferences.label.children');
    }
}