<?php

namespace Jiannius\Atom\Http\Livewire\App\Payment;

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
        breadcrumbs()->home('Payments');
    }

    /**
     * Get query property
     */
    public function getQueryProperty(): mixed
    {
        return model('payment')
            ->with('order')
            ->filter($this->filters)
            ->when(!$this->sort, fn($q) => $q->latest());
    }

    /**
     * Get table columns
     */
    public function getTableColumns($query): array
    {
        return [
            [
                'name' => 'Date',
                'sort' => 'created_at',
                'datetime' => $query->created_at,
            ],
            [
                'name' => 'Receipt #',
                'sort' => 'number',
                'label' => $query->number,
                'href' => route('app.payment.update', [$query->id]),
            ],
            [
                'name' => 'Order #',
                'sort' => 'orders.number',
                'label' => $query->order->number,
                'href' => route('app.order.update', [$query->order_id]),
            ],
            [
                'name' => 'Mode',
                'label' => $query->mode,
            ],
            [
                'name' => 'Amount',
                'sort' => 'amount',
                'class' => 'text-right',
                'date' => currency($query->amount, $query->currency),
            ],
            [
                'name' => 'Status',
                'sort' => 'status',
                'status' => $query->status,
            ],
        ];
    }

    /**
     * Render
     */
    public function render(): mixed
    {
        return atom_view('app.payment.listing');
    }
}