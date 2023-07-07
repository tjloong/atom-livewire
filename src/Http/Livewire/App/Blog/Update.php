<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Traits\Livewire\WithBreadcrumbs;
use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Livewire\Component;

class Update extends Component
{
    use WithBreadcrumbs;
    use WithPopupNotify;
    
    public $blog;

    protected $listeners = ['submitted'];

    // mount
    public function mount($id): void
    {
        $this->blog = model('blog')->findOrFail($id);
    }

    // delete
    public function delete(): mixed
    {
        $this->blog->delete();

        return to_route('app.blog.listing');
    }

    // submitted
    public function submitted(): mixed
    {
        return $this->popup('Blog Updated.');
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.blog.update');
    }
}