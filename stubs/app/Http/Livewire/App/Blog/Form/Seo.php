<?php

namespace App\Http\Livewire\App\Blog\Form;

use Livewire\Component;

class Seo extends Component
{
    public $blog;

    protected $rules = [
        'blog.slug' => 'nullable',
        'blog.redirect_slug' => 'nullable',
        'blog.seo.title' => 'nullable',
        'blog.seo.description' => 'nullable',
    ];

    /**
     * Mount event
     * 
     * @return void
     */
    public function mount($blog)
    {
        $this->blog = $blog;
    }

    /**
     * Rendering livewire view
     * 
     * @return Response
     */
    public function render()
    {
        return view('livewire.app.blog.form.seo');
    }

    /**
     * Handle save
     * 
     * @return void
     */
    public function save()
    {
        $this->blog->save();

        $this->emitUp('saved');
    }
}