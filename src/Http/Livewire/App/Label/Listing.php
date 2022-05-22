<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Livewire\Component;

class Listing extends Component
{
    public $type;
    public $sortedLabels;

    protected $queryString = ['type'];

    /**
     * Mount
     */
    public function mount()
    {
        $this->type = $this->type ?? $this->types[0];

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
     * Get labels property
     */
    public function getLabelsProperty()
    {
        return model('label')
            ->when(model('label')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->where('type', $this->type)
            ->orderBy('seq')
            ->orderBy('name')
            ->get();
    }

    /**
     * Sort labels
     */
    public function sortLabels($data = null)
    {
        if (!$data) return;

        foreach ($data as $index => $id) {
            model('label')->where('id', $id)->update(['seq' => $index + 1]);
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