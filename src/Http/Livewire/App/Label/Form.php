<?php

namespace Jiannius\Atom\Http\Livewire\App\Label;

use Livewire\Component;

class Form extends Component
{
    public $types;
    public $label;

    /**
     * Validation rules
     */
    protected function rules()
    {
        return [
            'label.name' => 'required|max:255',
            'label.type' => 'required',
            'label.slug' => 'nullable',
        ];
    }

    /**
     * Validation messages
     */
    protected function messages()
    {
        return [
            'label.name' => 'Label name is required.',
            'label.type' => 'Label type is required.',
        ];
    }

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->label->save();

        $this->emitUp('saved', $this->label->type);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.label.form');
    }
}