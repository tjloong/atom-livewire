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
        if (!$this->type) return redirect()->route('label.listing', [$this->types[0]]);

        $this->labels = $this->getLabels();

        breadcrumbs()->home('Labels');
    }

    /**
     * Get types property
     */
    public function getTypesProperty()
    {
        return config('atom.labels') ?? [];
    }

    /**
     * Get labels
     */
    public function getLabels()
    {
        return model('label')
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
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.listing', [
            'types' => $this->types,
        ]);
    }
}