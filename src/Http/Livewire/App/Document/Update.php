<?php

namespace Jiannius\Atom\Http\Livewire\App\Document;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Update extends Component
{
    use AuthorizesRequests;

    public $document;

    /**
     * Mount
     */
    public function mount($documentId)
    {
        $this->document = model('document')->readable()->findOrFail($documentId);

        $this->authorize($this->document->type.'.update');

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