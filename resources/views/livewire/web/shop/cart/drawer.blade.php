<x-drawer id="cart" header="Shopping Cart">
    <div x-data x-on:cart-open.window="$wire.emit('loadOrder')">
        @livewire(atom_lw('web.shop.cart.item'), [
            'small' => true,
            'order' => $order,
        ], key('item-'.uniqid()))

        @if (($order->items ?? collect())->count())
            <x-slot:foot>
                <div class="border-t flex flex-col divide-y">
                    <div class="p-4 flex items-center justify-between gap-3 flex-wrap">
                        <div class="shrink-0 text-lg font-semibold">{{ __('Subtotal') }}</div>
                        <div class="shrink-0 text-lg font-semibold">{{ currency($order->subtotal) }}</div>
                    </div>
                    
                    <div class="bg-slate-100 p-4">
                        <div class="max-w-sm mx-auto">
                            <x-button icon="arrow-right" color="theme" block
                                label="Check Out"
                                :href="route('web.shop.checkout')"
                            />
                        </div>
                    </div>
                </div>

            </x-slot:foot>
        @endif
    </div>
</x-drawer>