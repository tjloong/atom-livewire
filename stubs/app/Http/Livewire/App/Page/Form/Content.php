<?php

namespace App\Http\Livewire\App\Page\Form;

use Livewire\Component;

class Content extends Component
{
    public $page;
    public $autosavedAt;

    protected $rules = [
        'page.title' => 'required|string|max:255',
        'page.content' => 'nullable',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount($page)
    {
        $this->page = $page;
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.page.form.content');
    }

    /**
     * Save page when content is updated
     * 
     * @return void
     */
    public function updatedPageContent()
    {
        $this->page->save();
        $this->autosavedAt = now();
    }

    /**
     * Handle save
     * 
     * @return void
     */
    public function save()
    {
        $this->validateinputs();

        $this->page->save();

        $this->emitUp('saved');
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    private function validateinputs()
    {
        $this->resetValidation();

        $validator = validator(
            ['page' => $this->page],
            $this->rules,
            [
                'page.title.required' => 'Page title is required.',
                'page.title.max' => 'Page title has a maximum of 255 characters.',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}