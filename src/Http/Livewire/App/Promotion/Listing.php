<?php

namespace Jiannius\Atom\Http\Livewire\App\Promotion;

use Livewire\Component;
use Livewire\WithPagination;

class Listing extends Component
{
    use WithPagination;

    public $sortBy = 'updated_at';
    public $sortOrder = 'desc';
    public $filters = [
        'status' => '',
        'search' => '',
    ];

    protected $queryString = [
        'page' => ['except' => 1],
        'filters' => ['except' => [
            'status' => '',
            'search' => '',
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
            ->paginate(30);
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
     * Reset filters
     */
    public function resetFilters()
    {
        $this->reset('filters');
    }

    /**
     * Render
     */
    public function render()
    {
        return atom_view('app.promotion.listing');
    }
}
