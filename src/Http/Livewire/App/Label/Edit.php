<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $type;
    public $label;
    public $inputs;

    protected $listeners = [
        'editLabel' => 'open',
    ];

    // validation
    protected function validation() : array
    {
        return [
            'label.type' => ['nullable'],
            'label.slug' => ['nullable'],
            'label.color' => ['nullable'],
            'label.image_id' => ['nullable'],
            'label.parent_id' => ['nullable'],

            ...(
                $this->locales->mapWithKeys(fn($locale, $key) => [
                    'inputs.name.'.$locale => ['required' => 'Label name ('.$locale.') is required.'],
                ])->toArray()
            ),
        ];
    }

    // get locales property
    public function getLocalesProperty() : mixed
    {
        return collect(config('atom.locales'))->sort();
    }

    // open
    public function open($data = []) : void
    {
        $id = get($data, 'id');

        if (
            $this->label = $id
            ? model('label')->find($id)
            : model('label')->fill($data)
        ) {
            $this->resetValidation();

            $this->fill([
                'inputs.name' => (array) $this->label->name,
                'inputs.data' => $this->label->data ?: [],
            ]);
    
            $this->overlay();
        }
    }

    // delete
    public function delete() : void
    {
        $this->label->delete();
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

        $this->close();
    }

    // close
    public function close() : void
    {
        if ($this->label->parent) $this->open(['id' => $this->label->parent->id]);
        else $this->overlay(false);
    }
}