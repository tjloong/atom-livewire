<div class="p-5 bg-white rounded-md border shadow grid gap-1.5">
    @if ($attributes->has('title'))
        <div class="font-semibold text-gray-500">
            {{ $attributes->get('title') }}
        </div>
    @endif

    <div {{ $attributes->merge(['class' => 'text-5xl font-bold']) }}>
        {{ $slot }}
    </div>
</div>