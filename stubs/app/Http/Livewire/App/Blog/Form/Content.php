<?php

namespace App\Http\Livewire\App\Blog\Form;

use App\Models\Blog;
use Livewire\Component;

class Content extends Component
{
    public Blog $blog;
    public $autosavedAt;

    protected $rules = [
        'blog.title' => 'required|string|max:255',
        'blog.excerpt' => 'nullable',
        'blog.content' => 'nullable',
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
        return view('livewire.app.blog.form.content');
    }

    /**
     * Save blog when content is updated
     * 
     * @return void
     */
    public function updatedBlogContent($val)
    {
        $this->autosavedAt = null;
        if ($val && ($this->blog->exists || $this->blog->title)) $this->autosave();
    }

    /**
     * Handle autosave
     * 
     * @return void
     */
    public function autosave()
    {
        $this->blog->save();
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

        $this->blog->save();
        
        $this->emitUp('saved', $this->blog->id);
    }

    /**
     * Validate inputs
     * 
     * @return void
     */
    private function validateinputs()
    {
        $this->resetValidation();

        $validator = validator(['blog' => $this->blog], $this->rules, [
            'blog.title.required' => 'Blog title is required.',
            'blog.title.max' => 'Blog title has a maximum of 255 characters.',
        ]);
        
        if ($validator->fails()) {
            $this->dispatchBrowserEvent('toast', 'formError');
            $validator->validate();
        }
    }
}