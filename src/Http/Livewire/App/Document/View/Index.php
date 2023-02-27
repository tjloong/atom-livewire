<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Jiannius\Atom\Traits\Livewire\WithShareable;
use Livewire\Component;

class Index extends Component
{
    use WithShareable;

    public $document;

    /**
     * Mount
     */
    public function mount($documentId)
    {
        $this->document = model('document')->readable()->findOrFail($documentId);

        breadcrumbs()->push('#'.$this->document->number);
    }

    /**
     * Get title property
     */
    public function getTitleProperty()
    {
        $type = $this->document->type;
        
        if ($type === 'delivery-order') $prefix = 'DO';
        else if ($type === 'purchase-order') $prefix = 'PO';
        else if ($type === 'sales-order') $prefix = 'SO';
        else $prefix = str()->title($type);

        return $prefix.' #'.$this->document->number;
    }

    /**
     * Created shareable
     */
    public function createdShareable($shareable)
    {
        $this->document->fill([
            'shareable_id' => $shareable->id,
        ])->saveQuietly();
    }

    /**
     * Delete
     */
    public function delete()
    {
        $this->document->delete();

        return redirect()->route('app.document.listing', [$this->document->type]);
    }

    /**
     * PDF
     */
    public function pdf()
    {
        return $this->document->pdf();
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.document.view');
    }
}