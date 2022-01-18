<?php

namespace App\Http\Livewire\App\Label;

use App\Models\Label;
use Livewire\Component;

class Form extends Component
{
    public $types;
    public Label $label;

    protected $rules = [
        'label.name' => 'required|max:255',
        'label.type' => 'required',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount()
    {
        //
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.label.form');
    }

    /**
     * Save label
     * 
     * @return void
     */
    public function save()
    {
        $this->validateinputs();

        $this->label->save();

        $this->emitUp('saved', $this->label->type);
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    public function validateinputs()
    {
        $this->resetValidation();
        
        $validator = validator(['label' => $this->label], $this->rules, [
            'label.name' => 'Label name is required.',
            'label.type' => 'Label type is required.',
        ]);

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}