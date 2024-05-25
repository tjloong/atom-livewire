<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Update extends Component
{
    use WithForm;

    public $blog;
    public $inputs;

    protected $listeners = [
        'createBlog' => 'create',
        'updateBlog' => 'update',
    ];

    // validation
    protected function validation() : array
    {
        return [
            'blog.name' => [
                'required' => 'Blog title is required.',
                'string' => 'Blog title must be string.',
                'max:255' => 'Blog title too long (Max 255 characters).',
            ],
            'blog.description' => ['nullable'],
            'blog.redirect_to' => ['nullable'],
            'blog.content' => ['nullable'],
            'blog.seo' => ['nullable'],
            'blog.published_at' => ['nullable'],
            'blog.cover_id' => ['nullable'],
        ];
    }

    // create
    public function create() : void
    {
        $this->blog = model('blog');
        $this->open();
    }

    // update
    public function update($id) : void
    {
        $this->blog = model('blog')->withTrashed()->find($id);
        $this->open();
    }

    // open
    public function open() : void
    {
        if ($this->blog) {
            $this->fill([
                'inputs.labels' => $this->blog->labels->pluck('id')->toArray(),
                'inputs.seo' => [
                    'title' => data_get($this->blog->seo, 'title'),
                    'description' => data_get($this->blog->seo, 'description'),
                    'image' => data_get($this->blog->seo, 'image'),
                ],
            ]);

            $this->modal(id: 'blog-update');
        }
    }

    // close
    public function close() : void
    {
        $this->emit('setBlogId');
        $this->modal(false, 'blog-update');
    }

    // trash
    public function trash() : void
    {
        $this->blog->delete();
        $this->emit('blogDeleted');
        $this->close();
    }

    // delete
    public function delete() : void
    {
        $this->blog->forceDelete();
        $this->emit('blogDeleted');
        $this->close();
    }

    // restore
    public function restore() : void
    {
        $this->blog->restore();
        $this->emit('blogUpdated');
        $this->close();
    }

    // publish
    public function publish($bool) : void
    {
        $this->blog->fill([
            'published_at' => $bool ? now() : null,
        ])->save();

        $this->emit('blogUpdated');
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->blog->fill([
            'seo' => data_get($this->inputs, 'seo'),
        ])->save();
        
        $this->blog->labels()->sync(data_get($this->inputs, 'labels'));

        if ($this->blog->wasRecentlyCreated) $this->emit('blogCreated');
        else $this->emit('blogUpdated');

        $this->close();
    }
}