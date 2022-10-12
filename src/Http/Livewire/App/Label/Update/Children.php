<?php

namespace Jiannius\Atom\Http\Livewire\App\Label\Update;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Children extends Component
{
    use WithPopupNotify;

    public $label;
    public $locales;
    public $children;

    protected $listeners = ['childUpdated' => 'getChildren'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->getChildren();
    }

    /**
     * Get children
     */
    public function getChildren()
    {
        $this->children = $this->label->children()->orderBy('seq')->get();
    }

    /**
     * Sort
     */
    public function sort($data = null)
    {
        foreach ($data as $index => $id) {
            $this->label->children()->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->getChildren();
        $this->popup('Label Children Sorted');
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        $child = $this->label->children()->find($id);
        $child->delete();

        $this->getChildren();
        $this->popup('Label Child Deleted');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.update.children');
    }
}