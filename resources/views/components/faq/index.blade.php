<div {{ $attributes->merge(['class' => 'py-20 px-6']) }}>
    @if ($title = $attributes->get('title'))
        <div class="text-3xl font-bold pb-4">{{ __($title) }}</div>
    @elseif (isset($title))
        {{ $title }}
    @endif

    @if ($subtitle = $attributes->get('subtitle'))
        <div class="font-medium text-gray-500">{{ __($subtitle) }}</div>
    @elseif (isset($subtitle))
        {{ $subtitle }}
    @endif

    <div class="mt-6">
        {{ $slot }}
    </div>
</div>
