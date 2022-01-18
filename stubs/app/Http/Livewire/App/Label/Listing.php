<?php

namespace App\Http\Livewire\App\Label;

use App\Models\Label;
use Livewire\Component;

class Listing extends Component
{
    public $type;
    public $types;
    public $labels;

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        $this->types = [
            'blog-category',
        ];

        $this->getLabels();
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.label.listing');
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