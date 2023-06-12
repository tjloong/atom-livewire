<?php

namespace Jiannius\Atom\Http\Livewire\App\Order;

use Jiannius\Atom\Traits\Livewire\WithPopupNotify;
use Jiannius\Atom\Traits\Livewire\WithTable;
use Livewire\Component;

class Listing extends Component
{
    use WithTable;
    use WithPopupNotify;

    public $sort;

    public $filters = [
        'search' => null,
        'status' => null,
    ];

    /**
     * Mount
     */
    public function mount()
    {
        breadcrumbs()->home('Orders');
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): mixed
    {
        return model('order')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest())
            ->when(!data_get($this->filters, 'status'), fn($q) => $q->where('number', 'not like', "TEMP-%"));
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'checkbox' => $query->id,
            ],
            [
                'name' => 'Date',
                'sort' => 'created_at',
                'datetime' => $query->created_at,
            ],
            [
                'name' => 'Number',
                'sort' => 'number',
                'label' => $query->number,
                'href' => route('app.order.update', [$query->id]),
                'sortable_id' => $query->id,
            ],
            [
                'name' => 'Contact',
                'sort' => 'email',
                'label' => data_get($query->customer, 'email'),
                'small' => data_get($query->customer, 'phone'),
            ],
            [
                'name' => 'Grand Total',
                'sort' => 'grand_total',
                'class' => 'text-right',
                'date' => currency($query->grand_total, $query->currency),
            ],
            [
                'name' => 'Status',
                'sort' => 'status',
                'status' => $query->status,
            ],
        ];
    }

    /**
     * Mark
     */
    public function mark()
    {
        if ($this->checkboxes) {
            model('order')->whereIn('id', $this->checkboxes)
                ->get()
                ->each(fn($order) => $order->fill(['closed_at' => now()])->save());

            $this->resetCheckboxes();
        }
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.order.listing');
    }
}