<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog\Update;

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
        $this->blog->save();

        $this->emitUp('saved');
    }

    /**
     * Render
     */
    public function render()
    {
        return view('atom::app.blog.update.seo');
    }
}