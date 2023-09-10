<?php

namespace Jiannius\Atom\Http\Livewire\App\Settings\Label;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $type;
    public $label;
    public $inputs;

    protected $listeners = [
        'createLabel' => 'open',
        'updateLabel' => 'open',
    ];

    // validation
    protected function validation() : array
    {
        return array_merge(
            [
                'label.type' => ['nullable'],
                'label.slug' => ['nullable'],
                'label.parent_id' => ['nullable'],
            ],

            $this->locales->mapWithKeys(fn($locale, $key) => [
                'inputs.name.'.$locale => ['required' => 'Label name ('.$locale.') is required.'],
            ])->toArray(),
        );
    }

    // get locales property
    public function getLocalesProperty() : mixed
    {
        return collect(config('atom.locales'));
    }

    // open
    public function open($id = null, $type = null) : void
    {
        $this->resetValidation();

        if ($this->label = $id 
            ? model('label')->find($id)
            : model('label')->fill(['type' => $type ?? $this->type])
        ) {
            $this->fill([
                'inputs.name' => (array) $this->label->name,
            ]);

            $this->dispatchBrowserEvent('label-update-open');
        }
    }

    // close
    public function close() : void
    {
        $this->dispatchBrowserEvent('label-update-close');
    }

    // delete
    public function delete() : void
    {
        $this->label->delete();
        $this->emit('labelDeleted');
        $this->close();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->label->fill([
            'name' => data_get($this->inputs, 'name'),
            'slug' => null,
        ])->save();

        if ($this->label->wasRecentlyCreated) $this->emit('labelCreated');
        else $this->emit('labelUpdated');
        
        $this->close();
    }
}