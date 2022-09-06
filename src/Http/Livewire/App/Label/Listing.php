<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Traits\WithPopupNotify;
use Livewire\Component;

class Listing extends Component
{
    use WithPopupNotify;
    
    public $type;

    protected $queryString = ['type'];

    /**
     * Mount
     */
    public function mount()
    {
        if (!$this->type) $this->type = head($this->types);

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
            ->withCount('children')
            ->when(
                model('label')->enabledBelongsToAccountTrait, 
                fn($q) => $q->belongsToAccount()
            )
            ->where('type', $this->type)
            ->whereNull('parent_id')
            ->orderBy('seq')
            ->orderBy('name->'.app()->currentLocale())
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

        $this->popup('Labels Sorted');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.listing');
    }
}