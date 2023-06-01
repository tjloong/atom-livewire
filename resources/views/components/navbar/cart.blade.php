<div 
    x-cloak
    x-data
    x-on:click.stop="$dispatch('shopping-cart-open')"
    class="py-1.5 px-3 cursor-pointer"
>
    <div class="relative flex items-center justify-center gap-2">
        <x-icon name="cart-shopping lg"/>

        {{ $slot }}

        @if ($count = count(session('cart-items', [])))
            <span 
                class="absolute rounded-full bg-red-500 text-white flex items-center justify-center text-xs" 
                style="width: 18px; height: 18px; top: -18px; right: -10px;"
            >{{ $count }}</span>
        @endif
    </div>
</div>