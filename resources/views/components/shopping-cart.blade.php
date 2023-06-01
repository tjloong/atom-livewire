<x-drawer id="shopping-cart" header="Shopping Cart">
    @json(session('cart-items'))

    <x-slot:foot>
        <div class="bg-slate-100 p-4">
            <div class="max-w-sm mx-auto">
                <x-button label="Check Out" icon="arrow-right" color="theme" block/>
            </div>
        </div>
    </x-slot:foot>
</x-drawer>