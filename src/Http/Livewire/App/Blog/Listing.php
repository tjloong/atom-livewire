<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Component;
use Jiannius\Atom\Traits\Livewire\WithTable;

class Listing extends Component
{
    use WithTable;

    public $filters = [
        'search' => null,
        'status' => [],
    ];

    protected $listeners = [
        'closeBlog' => '$refresh',
    ];

    // get query property
    public function getQueryProperty() : mixed
    {
        return model('blog')->when(!$this->tableOrderBy, fn($q) => $q->latest());
    }
}