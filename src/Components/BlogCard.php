<?php

namespace Jiannius\Atom\Components;

use Illuminate\View\Component;

class BlogCard extends Component
{
    public function render()
    {
        return view('atom::components.blog-card');
    }
}