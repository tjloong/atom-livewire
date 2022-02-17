<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Livewire\Component;
use Jiannius\Atom\Models\Label;

class Listing extends Component
{
    public $type;
    public $types;
    public $labels;

    /**
     * Mount
     */
    public function mount($type = null)
    {
        breadcrumb(['home' => 'Labels']);
        
        $this->types = config('atom.features.labels') ?? [];

        if (!$type) return redirect()->route('label.listing', [$this->types[0]]);
        
        $this->getLabels();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('atom::app.label.listing');
    }

    /**
     * Updated labels
     * 
     * @return void
     */
    public function updatedLabels($labels)
    {
        foreach ($labels as $index => $label) {
            Label::where('id', $label['id'])->update(['seq' => $index + 1]);
        }
    }

    /**
     * Get labels
     * 
     * @return array
     */
    public function getLabels()
    {
        $this->labels = Label::query()
            ->where('type', $this->type)
            ->orderBy('seq')
            ->orderBy('name')
            ->get()
            ->toArray();
    }
}