<div
    x-data="{ value: @entangle($attributes->wire('model')->value()) }"
    class="inline-flex items-center flex-wrap text-sm"
    {{ $attributes }}
>
    {{ $slot }}
</div>