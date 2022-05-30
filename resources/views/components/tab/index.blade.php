<div
    x-data="{ value: @entangle($attributes->wire('model')) }"
    class="inline-flex items-center flex-wrap"
    {{ $attributes }}
>
    {{ $slot }}
</div>
