<?php

namespace Jiannius\Atom\Components\Blog;

use Illuminate\View\Component;

class Index extends Component
{
    /**
     * Render
     */
    public function render()
    {
        return view('atom::components.blog.index');
    }
}