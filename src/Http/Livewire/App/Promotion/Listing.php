<?php

namespace Jiannius\Atom\Http\Livewire\App\Promotion;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;
    use WithTable;

    public $sortBy = 'updated_at';
    public $sortOrder = 'desc';
    public $filters = [
        'status' => null,
        'search' => null,
    ];

    protected $queryString = [
        'page' => ['except' => 1],
        'filters' => ['except' => [
            'status' => null,
            'search' => null,
        ]],
        'sortBy' => ['except' => 'updated_at'],
        'sortOrder' => ['except' => 'desc'],
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Promotions');
    }

    /**
     * Get promotions property
     */
    public function getPromotionsProperty()
    {
        return model('promotion')
            ->when(model('promotion')->enabledBelongsToAccountTrait, fn($q) => $q->belongsToAccount())
            ->filter($this->filters)
            ->orderBy($this->sortBy, $this->sortOrder)
            ->paginate($this->maxRows);
    }

    /**
     * Get options property
     */
    public function getOptionsProperty()
    {
        return [
            'statuses' => collect(['active', 'inactive', 'ended'])->map(fn($val) => [
                'value' => $val,
                'label' => str()->headline($val),
            ]),
        ];
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.promotion.listing');
    }
}
