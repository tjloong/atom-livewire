<label
    class="inline-flex gap-2"
    x-data="{ get show () { return this.$refs.checkbox.checked }}"
>
    <input
        x-ref="checkbox"
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

    <div class="flex flex-col">
        <div class="flex-grow flex items-center font-normal">
            {{ $slot }}
        </div>
    </div>
</label>
