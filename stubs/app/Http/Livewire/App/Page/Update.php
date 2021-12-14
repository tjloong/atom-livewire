<?php

namespace App\Http\Livewire\App\Page;

use App\Models\Page;
use Livewire\Component;

class Update extends Component
{
    public Page $page;
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
        return view('livewire.app.page.update');
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
        $this->dispatchBrowserEvent('toast', ['message' => 'Page Updated', 'type' => 'success']);
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