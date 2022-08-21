<div
    x-data="{ value: @entangle($attributes->wire('model')) }"
    class="inline-flex items-center flex-wrap gap-4"
    {{ $attributes }}
>
    {{ $slot }}
</div>
