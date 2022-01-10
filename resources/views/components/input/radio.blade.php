<label 
    x-data="{ checked: false }"
    x-on:radio-checked.window="checked = $event.detail.name === '{{ $attributes->get('name') }}' && $event.detail.el.isEqualNode($refs.radio)"
    class="inline-flex gap-2"
>
    <input
        type="radio"
        x-ref="radio"
        x-on:change="$dispatch('radio-checked', { name: '{{ $attributes->get('name') }}', el: $event.target })"
        x-init="checked = $el.checked"
        class="absolute opacity-0"
        {{ $attributes }}
    >

    <div
        x-bind:class="checked ? 'border-theme' : 'border-gray-300'"
        class="w-5 h-5 bg-white border-2 flex-shrink-0 flex items-center justify-center rounded-full"
    >
        <div x-show="checked" class="w-3 h-3 drop-shadow bg-theme rounded-full"></div>
    </div>

    <div class="flex flex-col">
        <div class="flex-grow flex items-center font-normal">
            {{ $slot }}
        </div>
    </div>
</label>
