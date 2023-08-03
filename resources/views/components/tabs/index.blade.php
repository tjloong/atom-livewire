<div
    x-data="{
        value: @entangle($attributes->wire('model')),
    }"
    {{ $attributes->class(['relative w-full']) }}
>
    <div class="relative inline-flex flex-wrap items-center text-gray-500 select-none w-full p-1 bg-gray-100 rounded-lg mb-4">
        {{ $slot }}
    </div>
</div>