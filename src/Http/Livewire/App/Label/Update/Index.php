<?php

namespace Jiannius\Atom\Http\Livewire\App\Label\Update;

use Livewire\Component;

class Index extends Component
{
    public $label;
    
    /**
     * Mount
     */
    public function mount($label)
    {
        $this->label = model('label')
            ->when(model('label')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->findOrFail($label);

        breadcrumbs()->push($this->label->locale('name'));
    }

    /**
     * Get locales property
     */
    public function getLocalesProperty()
    {
        $locales = array_merge(array_keys((array)$this->label->name), config('atom.locales'));

        return collect($locales)->unique()->values();
    }

    /**
     * Get enable children property
     */
    public function getEnableChildrenProperty()
    {
        return false;
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->label->delete();

        return redirect()->route('app.label.listing', [$this->label->type])->with('info', 'Label Deleted');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.update.index');
    }
}