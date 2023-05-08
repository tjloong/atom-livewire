<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithPopupNotify;
    
    public $blog;

    protected $listeners = ['submitted'];

    /**
     * Mount
     */
    public function mount($blogId): void
    {
        $this->blog = model('blog')->findOrFail($blogId);

        breadcrumbs()->push($this->blog->title);
    }

    /**
     * Delete
     */
    public function delete(): mixed
    {
        $this->blog->delete();

        return breadcrumbs()->back();
    }

    /**
     * Submitted
     */
    public function submitted(): mixed
    {
        return $this->popup('Blog Updated.');
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.blog.update');
    }
}