<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog\Form;

use Livewire\Component;

class Seo extends Component
{
    public $blog;

    protected $rules = [
        'blog.slug' => 'nullable',
        'blog.redirect_slug' => 'nullable',
        'blog.seo' => 'nullable',
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
        return view('atom::app.blog.form.seo');
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