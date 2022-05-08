<div class="grid gap-2 mb-6">
    @if ($name = $attributes->get('name'))
        <div x-bind:class="!show && 'hidden md:block'" class="text-sm text-gray-400 font-medium uppercase px-3">
            {{ $name }}
        </div>
    @endif

    <div>
        {{ $slot }}
    </div>
</div>
