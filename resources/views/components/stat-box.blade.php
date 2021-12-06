<div class="p-5 bg-white rounded-md border drop-shadow">
    @if ($attributes->has('title'))
        <div class="font-semibold text-gray-500">
            {{ $attributes->get('title') }}
        </div>
    @endif

    <div {{ $attributes->merge(['class' => 'text-4xl font-bold']) }}>
        {{ $slot }}
    </div>
</div>