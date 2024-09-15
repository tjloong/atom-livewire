<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithForm;

class Edit extends Component
{
    use WithForm;

    public $blog;
    public $inputs;

    protected $listeners = [
        'editBlog' => 'open',
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

    // open
    public function open($data = []) : void
    {
        $id = get($data, 'id');

        if (
            $this->blog = $id
            ? model('blog')->withTrashed()->find($id)
            : model('blog')->fill([
                'status' => enum('blog.status', 'DRAFT'),
                ...$data,
            ])
        ) {
            $this->fill([
                'inputs.labels' => $this->blog->labels->pluck('id')->toArray(),
                'inputs.seo' => $this->blog->getSeo(),
            ]);

            $this->overlay();
        }
    }

    // trash
    public function trash() : void
    {
        $this->blog->delete();
        $this->overlay(false);
    }

    // delete
    public function delete() : void
    {
        $this->blog->forceDelete();
        $this->overlay(false);
    }

    // restore
    public function restore() : void
    {
        $this->blog->restore();
        $this->overlay(false);
    }

    // publish
    public function publish($bool) : void
    {
        $this->blog->fill([
            'published_at' => $bool ? now() : null,
        ])->save();
    }

    // submit
    public function submit() : void
    {
        $this->validateForm();

        $this->blog->fill([
            'seo' => data_get($this->inputs, 'seo'),
        ])->save();
        
        $this->blog->labels()->sync(data_get($this->inputs, 'labels'));

        $this->overlay(false);
    }
}