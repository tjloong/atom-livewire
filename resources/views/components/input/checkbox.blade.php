<label
    class="inline-flex gap-2"
    x-data="{ get show () { return this.$refs.checkbox.checked }}"
    @if ($attributes->get('disabled')) disabled @endif
>
    <input
        x-ref="checkbox"
        type="checkbox"
        class="hidden"
        {{ $attributes }}
    >

    @if ($attributes->get('disabled'))
        <div x-ref="box"
            x-bind:class="show ? 'border-gray-500' : 'border-gray-300'"
            class="w-5 h-5 bg-white m-1 border-2 flex-shrink-0 flex items-center justify-center rounded"
        >
            <div class="w-3 h-3 shadow bg-gray-500" x-show="show"></div>
        </div>
    @else
        <div x-ref="box"
            x-bind:class="show ? 'border-blue-700' : 'border-gray-300'"
            class="w-5 h-5 bg-white m-1 border-2 flex-shrink-0 flex items-center justify-center rounded"
        >
            <div class="w-3 h-3 shadow bg-blue-700" x-show="show"></div>
        </div>
    @endif

    <div class="flex flex-col">
        <div class="flex-grow flex items-center font-normal">
            {{ $slot }}
        </div>
    </div>
</label>
