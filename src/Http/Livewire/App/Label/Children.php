<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Illuminate\Database\Eloquent\Collection;
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
    public function getChildrenProperty(): Collection
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
    public function getPaddingProperty(): float
    {
        return $this->depth * 1.5;
    }

    /**
     * Sort
     */
    public function sort($data): void
    {
        foreach ($data as $seq => $id) {
            model('label')->find($id)->fill(['seq' => $seq])->saveQuietly();
        }
    }

    /**
     * Delete
     */
    public function delete($id): void
    {
        $this->emitUp('delete', $id);
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.label.children');
    }
}