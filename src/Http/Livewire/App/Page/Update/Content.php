<?php

namespace Jiannius\Atom\Http\Livewire\App\Page\Update;

use Livewire\Component;

class Content extends Component
{
    public $page;
    public $autosavedAt;
    
    protected $rules = [
        'page.title' => 'required|string|max:255',
        'page.slug' => 'required',
        'page.content' => 'nullable',
    ];

    protected $messages = [
        'page.title.required' => 'Page title is required.',
        'page.title.max' => 'Page title has a maximum of 255 characters.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Update page content
     */
    public function updatedPageContent()
    {
        $this->page->save();
        $this->autosavedAt = now();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->page->save();
        
        $this->dispatchBrowserEvent('toast', ['message' => 'Page Updated', 'type' => 'success']);
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.page.update.content');
    }
}