<label
    class="inline-flex font-normal space-x-1.5"
    x-data="{ show: false }"
>
    <input
        x-ref="checkbox"
        x-on:change="show = $el.checked"
        x-init="show = $el.checked"
        type="checkbox"
        class="hidden"
        {{ $attributes }}
    >

    <div
        x-ref="box"
        class="w-5 h-5 bg-white m-1 border-2 flex-shrink-0 flex items-center justify-center rounded"
        x-bind:class="show ? 'border-theme' : 'border-gray-300'"
    >
        <div class="w-3 h-3 shadow bg-theme" x-show="show"></div>
    </div>

    <div class="text-sm flex items-center self-center h-full">
        {{ $slot }}
    </div>
</label>
