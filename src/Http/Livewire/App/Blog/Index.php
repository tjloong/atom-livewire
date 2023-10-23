<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Component;

class Index extends Component
{
    public $blogId;

    protected $listeners = [
        'setBlogId',
        'updateBlog' => 'setBlogId',
    ];

    // set blog id
    public function setBlogId($id = null) : void
    {
        $this->fill(['blogId' => $id ?: null]);
    }
}