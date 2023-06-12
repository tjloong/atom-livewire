<?php

namespace Jiannius\Atom\Http\Livewire\App\Tax;

use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;

    public $onboarding;

    /**
     * Get query property
     */
    public function getQueryProperty()
    {
        return model('tax')
            ->readable()
            ->orderBy('country')
            ->orderBy('state')
            ->orderBy('name');
    }

    /**
     * Get table column
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Tax',
                'sort' => 'name',
                'label' => $query->name,
                'href' => route('app.tax.update', [$query->id]),
            ],

            [
                'name' => 'Rate',
                'sort' => 'rate',
                'label' => str($query->rate)->finish('%'),
            ],

            [
                'name' => 'Region',
                'class' => 'text-right',
                'label' => collect([$query->state, $query->country])->filter()->join(', '),
            ],

            [
                'name' => 'Status',
                'status' => $query->is_active ? 'active' : 'inactive',
            ],
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.tax.listing');
    }
}