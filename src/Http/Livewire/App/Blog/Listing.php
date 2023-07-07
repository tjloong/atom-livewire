<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    // get title property
    public function getTitleProperty(): string
    {
        return 'Blogs';
    }

    // get query property
    public function getQueryProperty(): mixed
    {
        return model('blog')->readable()
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest('updated_at'));
    }

    // get table columns
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Title',
                'label' => $query->title,
                'href' => route('app.blog.update', [$query->id]),
            ],

            [
                'name' => 'Category',
                'tags' => $query->labels->pluck('name.'.app()->currentLocale())->toArray(),
            ],

            [
                'name' => 'Updated',
                'class' => 'text-right',
                'from_now' => $query->updated_at,
            ],

            [
                'name' => 'Status',
                'status' => $query->status,
            ],
        ];
    }

    // render
    public function render(): mixed
    {
        return atom_view('app.blog.listing');
    }
}