<?php

namespace Jiannius\Atom\Http\Livewire\App\Document\Form;

use Illuminate\Support\Arr;
use Livewire\Component;

class AdditionalInfo extends Component
{
    public $inputs;
    public $document;

    protected $rules = [
        'inputs.owned_by' => 'nullable',
        'inputs.labels' => 'nullable',
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        $this->inputs = [
            'owned_by' => $this->document->owned_by,
            'labels' => $this->document->labels->pluck('id')->toArray(),
        ];
    }

    /**
     * Updated inputs
     */
    public function updatedInputs(): void
    {
        $this->document->labels()->sync(data_get($this->inputs, 'labels'));
        $this->emitUp('setDocument', Arr::only($this->inputs, ['owned_by']));
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.document.form.additional-info');
    }
}