<div 
    x-data="{
        count: @js(collect(session('cart-items', []))->sum('qty')),
    }"
    x-on:click="$dispatch('cart-open')"
    x-on:cart-count.window="count = $event.detail"
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