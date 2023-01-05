<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\View;

use Jiannius\Atom\Traits\Livewire\WithShareable;
use Livewire\Component;

class Index extends Component
{
    use WithShareable;

    public $document;

    protected $listeners = ['refresh' => '$refresh'];

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
     * Get actions property
     */
    public function getActionsProperty()
    {
        return collect([
            'pdf' => $this->document->type !== 'bill',
            'edit' => auth()->user()->can($this->document->type.'.update'),
            'send' => in_array($this->document->type, ['quotation', 'invoice']),
            'split' => $this->document->type === 'invoice',
            'share' => $this->document->type !== 'bill',
            'delete' => auth()->user()->can($this->document->type.'.delete'),
            'bill' => $this->document->type === 'purchase-order',
            'invoice' => in_array($this->document->type, ['quotation', 'sales-order']),
            'payment' => in_array($this->document->type, ['invoice', 'bill']),
        ]);
    }

    /**
     * Open email modal
     */
    public function openEmailModal()
    {
        $this->emitTo(lw('app.document.view.email-form-modal'), 'open');
    }

    /**
     * Toggle sent
     */
    public function toggleSent()
    {
        $this->document->fill([
            'last_sent_at' => $this->document->last_sent_at
                ? null
                : now(),
        ])->saveQuietly();
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