<?php

namespace Jiannius\Atom\Http\Livewire\App\Promotion;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $sort = 'updated_at,desc';
    public $filters = [
        'status' => null,
        'search' => null,
    ];

    protected $queryString = [
        'filters' => ['except' => [
            'status' => null,
            'search' => null,
        ]],
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
            ->when(model('promotion')->enabledHasTenantTrait, fn($q) => $q->belongsToTenant())
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
