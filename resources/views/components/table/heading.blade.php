<div class="flex flex-wrap items-center gap-2 p-3">
    <div class="grow px-2">
        <x-heading {{ $attributes }} sm/>
    </div>

    <div class="shrink-0">
        {{ $slot }}
    </div>
</div>
