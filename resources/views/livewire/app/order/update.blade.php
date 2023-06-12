<div class="max-w-screen-lg mx-auto">
    <x-page-header :title="'Order #'.$order->number" :status="$order->status" back>
        <x-dropdown>
            <x-slot:anchor>
                <x-button label="More" icon="gear"/>
            </x-slot:anchor>

            @if ($order->status === 'pending')
                <x-dropdown.delete
                    title="Delete Order"
                    message="Are you sure to DELETE this order?"
                />
            @elseif ($order->status === 'closed')
                <x-dropdown.item label="Unmark as closed" icon="undo" wire:click="mark('closed', false)"/>
            @elseif ($order->status === 'shipped')
                <x-dropdown.item label="Unmark as shipped" icon="undo" wire:click="mark('shipped', false)"/>
            @else
                <x-dropdown.item label="Mark as shipped" wire:click="mark('shipped')"/>
                <x-dropdown.item label="Mark as closed" wire:click="mark('closed')"/>
            @endif
        </x-dropdown>
    </x-page-header>

    <x-document 
        :contact="[
            'Email' => data_get($order->customer, 'email'),
            'Phone' => data_get($order->customer, 'phone'),
            'Shipping Address' => [
                'name' => collect($order->shipping)->only('first_name', 'last_name')->filter()->join(' '),
                'company' => data_get($order->shipping, 'company'),
                'address' => format_address($order->shipping),
            ],
            'Billing Address' => data_get($order->billing, 'same_as_shipping') 
                ? __('Same as shipping address') 
                : [
                    'name' => collect($order->shipping)->only('first_name', 'last_name')->filter()->join(' '),
                    'company' => data_get($order->shipping, 'company'),
                    'address' => format_address($order->shipping),
                ],
        ]"
        :info="[
            'Order #' => $order->number,
            'Order Date' => format_date($order->created_at, 'datetime'),
            'Closed Date' => format_date($order->closed_at, 'datetime') ?? '--',
            'Shipping Method' => optional($order->shipping_rate)->name ?? '--',
            'Coupon' => optional($order->coupon)->code ?? '--',
            'Receipt #' => [
                'value' => optional($this->payment)->number ?? '--',
                'href' => $this->payment ? route('app.payment.update', [$this->payment->id]) : null,
            ],
        ]"
        :items="$order->items"
        :total="[
            'Subtotal' => currency($order->subtotal, $order->currency),
            'Grand Total' => currency($order->grand_total, $order->currency),
        ]"
    />
</div>