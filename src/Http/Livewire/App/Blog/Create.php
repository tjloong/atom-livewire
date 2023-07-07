<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Traits\Livewire\WithBreadcrumbs;
use Livewire\Component;

class Create extends Component
{
    use WithBreadcrumbs;
    
    public $blog;

    protected $listeners = ['submitted'];

    // mount
    public function mount(): void
    {
        $this->blog = model('blog');
    }

    // get title property
    public function getTitleProperty(): string
    {
        return 'Create Blog';
    }

    // submitted
    public function submitted($id): mixed
    {
        return to_route('app.blog.update', [$id]);
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.blog.create');
    }
}