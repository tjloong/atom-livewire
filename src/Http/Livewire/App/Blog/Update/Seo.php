<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog\Update;

use Jiannius\Atom\Traits\Livewire\WithForm;
use Livewire\Component;

class Seo extends Component
{
    use WithForm;

    public $blog;

    /**
     * Validation
     */
    protected function validation(): array
    {
        return [
            'blog.slug' => 'nullable',
            'blog.redirect_slug' => 'nullable',
            'blog.seo' => 'nullable',
        ];
    }

    /**
     * Submit
     */
    public function submit(): void
    {
        $this->blog->save();

        $this->emitUp('saved');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.blog.update.seo');
    }
}