<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Livewire\Component;

class Listing extends Component
{
    public $type;
    public $labels;

    /**
     * Mount
     */
    public function mount()
    {
        $this->type = $this->type ?? $this->types[0];
        $this->labels = $this->getLabels();

        breadcrumbs()->home('Labels');
    }

    /**
     * Get types property
     */
    public function getTypesProperty()
    {
        return ['blog-category'];
    }

    /**
     * Get labels
     */
    public function getLabels()
    {
        return model('label')
            ->when(model('label')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->where('type', $this->type)
            ->orderBy('seq')
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    /**
     * Updated labels
     */
    public function updatedLabels($labels)
    {
        foreach ($labels as $index => $label) {
            model('label')->where('id', $label['id'])->update(['seq' => $index + 1]);
        }

        $this->dispatchBrowserEvent('toast', ['message' => __('Labels Updated'), 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.listing');
    }
}