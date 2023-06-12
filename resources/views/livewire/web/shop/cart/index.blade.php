<div class="min-h-screen py-10 px-4">
    <div class="max-w-screen-lg mx-auto">
        <x-page-header title="Your Shopping Cart"/>

        @if ($this->order && $this->order->items->count())
            <div class="flex flex-col gap-4">
                <x-box>
                    <div class="flex flex-col divide-y">
                        @livewire(atom_lw('web.shop.cart.item'), compact('order'), key('item-'.uniqid()))
                        @livewire(atom_lw('web.shop.cart.sum'), compact('order'), key('sum-'.uniqid()))
                    </div>
                </x-box>

                <div class="text-right">
                    <x-button icon="arrow-right" color="theme" size="md"
                        label="Checkout"
                        :href="route('web.shop.checkout')"
                    />
                </div>
            </div>
        @else
            <x-box>
                <x-empty-state title="No Items" subtitle="Your shopping cart is empty."/>
            </x-box>
        @endif
    </div>
</div>