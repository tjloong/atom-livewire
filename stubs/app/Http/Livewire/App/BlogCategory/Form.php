<?php

namespace App\Http\Livewire\App\BlogCategory;

use Illuminate\Support\Facades\Validator;
use Livewire\Component;

class Form extends Component
{
    public $label;

    protected $rules = [
        'label.name' => 'required|max:255',
        'label.type' => 'required',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount($label)
    {
        $this->label = $label;
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.blog-category.form');
    }

    /**
     * Save blog category
     * 
     * @return void
     */
    public function save()
    {
        $this->validateinputs();
        $this->label->save();
        $this->emitUp('saved');
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    public function validateinputs()
    {
        $this->resetValidation();
        
        $validator = Validator::make(
            ['label' => $this->label],
            $this->rules,
            ['label.name' => 'Category name is required.'],
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}