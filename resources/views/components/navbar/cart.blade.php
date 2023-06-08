<div 
    x-cloak
    x-data="{ count: @js(session('shop_cart_count')) }"
    x-on:shop-cart-count.window="count = $event.detail"

    @if (current_route('web.shop.cart')) x-on:click="window.location.reload()"
    @else x-on:click="$dispatch('cart-open')"
    @endif
    
    class="py-1.5 px-3 cursor-pointer"
>
    <div class="relative flex items-center justify-center gap-2">
        <x-icon name="cart-shopping lg"/>

        {{ $slot }}

        <span 
            x-text="count"
            x-show="count > 0"
            class="absolute rounded-full bg-red-500 text-white flex items-center justify-center text-xs" 
            style="width: 18px; height: 18px; top: -18px; right: -10px;"
        ></span>
    </div>
</div>