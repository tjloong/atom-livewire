<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog\Update;

use Livewire\Component;

class Content extends Component
{
    public $blog;
    public $autosavedAt;

    protected $rules = [
        'blog.title' => 'required|string|max:255',
        'blog.excerpt' => 'nullable',
        'blog.content' => 'nullable',
    ];

    protected $messages = [
        'blog.title.required' => 'Blog title is required.',
        'blog.title.max' => 'Blog title has a maximum of 255 characters.',
    ];

    /**
     * Mount
     */
    public function mount()
    {
        //
    }

    /**
     * Updated blog content
     */
    public function updatedBlogContent($val)
    {
        $this->autosavedAt = null;
        if ($val && ($this->blog->exists || $this->blog->title)) $this->autosave();
    }

    /**
     * Autosave
     */
    public function autosave()
    {
        $this->blog->save();
        $this->autosavedAt = now();
    }

    /**
     * Submit
     */
    public function submit()
    {
        $this->resetValidation();
        $this->validate();

        $this->blog->save();
        
        $this->emitUp('saved', $this->blog->id);
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.blog.update.content');
    }
}