<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;

    public $type;

    /**
     * Get types property
     */
    public function getTypesProperty()
    {
        return ['blog-category'];
    }

    /**
     * Sort
     */
    public function sort($data)
    {
        foreach ($data as $index => $id) {
            model('label')->where('id', $id)->update(['seq' => $index + 1]);
        }

        $this->popup('Labels Sorted');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.listing', [
            'labels' => $this->type
                ? model('label')
                    ->withCount('children')
                    ->when(
                        model('label')->enabledBelongsToAccountTrait, 
                        fn($q) => $q->belongsToAccount()
                    )
                    ->where('type', $this->type)
                    ->whereNull('parent_id')
                    ->orderBy('seq')
                    ->orderBy('name->'.app()->currentLocale())
                    ->get()
                : null,
        ]);
    }
}