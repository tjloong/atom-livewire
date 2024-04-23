<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $type;
    public $label;
    public $inputs;

    protected $listeners = [
        'createLabel' => 'create',
        'updateLabel' => 'update',
    ];

    // validation
    protected function validation() : array
    {
        return array_merge(
            [
                'label.type' => ['nullable'],
                'label.slug' => ['nullable'],
                'label.color' => ['nullable'],
                'label.image_id' => ['nullable'],
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

    // create
    public function create($data = []) : void
    {
        $this->label = model('label')->fill($data);
        $this->open();
    }

    // update
    public function update($id) : void
    {
        $this->label = model('label')->find($id);
        $this->open();
    }

    // open
    public function open() : void
    {
        if ($this->label) {
            $this->resetValidation();

            $this->fill([
                'inputs.name' => (array) $this->label->name,
                'inputs.data' => $this->label->data ?: [],
            ]);
    
            $this->openDrawer();
        }
    }

    // close
    public function close() : void
    {
        $this->closeDrawer();
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
            'name' => get($this->inputs, 'name'),
            'slug' => null,
            'data' => get($this->inputs, 'data') ?: null,
        ])->save();

        if ($this->label->wasRecentlyCreated) $this->emit('labelCreated');
        else $this->emit('labelUpdated');
        
        $this->close();
    }
}