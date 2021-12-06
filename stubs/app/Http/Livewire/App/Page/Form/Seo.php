<?php

namespace App\Http\Livewire\App\Page\Form;

use Livewire\Component;

class Seo extends Component
{
    public $page;

    protected $rules = [
        'page.slug' => 'nullable',
        'page.seo.title' => 'nullable',
        'page.seo.description' => 'nullable',
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
        return view('livewire.app.page.form.seo');
    }

    /**
     * Handle save
     * 
     * @return void
     */
    public function save()
    {
        $this->page->save();

        $this->emitUp('saved');
    }
}