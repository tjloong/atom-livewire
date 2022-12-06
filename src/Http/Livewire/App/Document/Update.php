<?php

namespace Jiannius\Atom\Http\Livewire\App\Document;

use Livewire\Component;

class Update extends Component
{
    public $document;

    /**
     * Mount
     */
    public function mount($documentId)
    {
        $this->document = model('document')
            ->when(
                model('document')->enabledBelongsToAccountTrait,
                fn($q) => $q->belongsToAccount(),
            )
            ->findOrFail($documentId);

        if ($master = $this->document->splittedFrom) {
            $this->document = $master;
        }

        breadcrumbs()->push($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        return str()->headline('Update '.$this->document->type);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.update');
    }
}