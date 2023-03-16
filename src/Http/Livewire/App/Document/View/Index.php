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
    public function mount($documentId): void
    {
        $this->document = model('document')->readable()->findOrFail($documentId);

        breadcrumbs()->push('#'.$this->document->number);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        $prefix = [
            'delivery-order' => 'DO',
            'purchase-order' => 'PO',
            'sales-order' => 'SO',
        ][$this->document->type] ?? null;

        return collect([$prefix, '#'.$this->document->number])->filter()->join(' ');
    }

    /**
     * Created shareable
     */
    public function createdShareable($shareable): void
    {
        $this->document->fill([
            'shareable_id' => $shareable->id,
        ])->saveQuietly();
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->document->delete();

        return breadcrumbs()->back();
    }

    /**
     * PDF
     */
    public function pdf(): mixed
    {
        return $this->document->pdf();
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.view');
    }
}