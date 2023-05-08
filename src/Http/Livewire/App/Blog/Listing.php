<?php

namespace Jiannius\Atom\Http\Livewire\App\Blog;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort = 'updated_at,desc';

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    /**
     * Mount
     */
    public function mount(): void
    {
        breadcrumbs()->home($this->title);
    }

    /**
     * Get title property
     */
    public function getTitleProperty(): string
    {
        return 'Blogs';
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): mixed
    {
        return model('blog')->readable()->filter($this->filters);
    }

    /**
     * Get table columns
     */
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

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.blog.listing');
    }
}