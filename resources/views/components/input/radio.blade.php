<label
    {{ $attributes->class([
        'radio inline-flex font-normal space-x-1.5',
        'active' => $attributes->get('checked'),
    ]) }}
    x-data="{
        toggle () {
            document.querySelectorAll(`input[type='radio'][name='{{ $attributes->get('name') }}']`).forEach(radio => {
                if (radio.checked) radio.parentNode.classList.add('active')
                else radio.parentNode.classList.remove('active')
            })
        }
    }"
>
    <input
        x-ref="radio"
        type="radio"
        class="absolute opacity-0"
        {{ $attributes }}
        x-on:change="toggle()"
    >

    <div class="radio-container w-5 h-5 bg-white m-1 border-2 flex-shrink-0 flex items-center justify-center rounded">
        <div class="radio-box w-3 h-3 shadow bg-theme"></div>
    </div>

    <div class="text-sm flex items-center h-full">
        {{ $slot }}
    </div>
</label>
