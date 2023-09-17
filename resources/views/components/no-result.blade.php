@props([
    'getSize' => function() use ($attributes) {
        if ($size = $attributes->get('size')) return $size;
        if ($attributes->get('sm')) return 'sm';
        if ($attributes->get('xs')) return 'xs';
        return 'md';
    },
])

<div class="flex justify-center 
    {{ [
        'xs' => 'gap-3 py-4',
        'sm' => 'flex-col items-center gap-3 py-8',
        'md' => 'flex-col items-center gap-3 py-8',
    ][$getSize()] }} 
    {{ $attributes->get('box') ? 'bg-white rounded-lg border shadow' : '' }}">
    @isset($icon) {{ $icon }}
    @elseif ($icon = $attributes->get('icon', 'folder-open'))
        <div class="rounded-full bg-white shadow flex border {{ [
            'xs' => 'w-8 h-8 text-sm',
            'sm' => 'w-12 h-12',
            'md' => 'w-20 h-20 text-3xl',
        ][$getSize()] }}">
            <x-icon :name="$icon" class="text-gray-400 m-auto"/>
        </div>
    @endisset

    <div class="flex flex-col gap-4">
        <div class="{{ [
            'xs' => '',
            'sm' => 'text-center',
            'md' => 'text-center',
        ][$getSize()] }}">
            <div class="font-semibold text-gray-800 {{ [
                'xs' => '',
                'sm' => '',
                'md' => 'text-lg',
            ][$getSize()] }}">
                {!! __($attributes->get('title', 'No Results')) !!}
            </div>

            @if ($subtitle = $attributes->get('subtitle', 'There is nothing returned from the search.'))
                <div class="text-gray-400 font-medium {{ [
                    'xs' => 'text-sm',
                    'sm' => 'text-sm',
                    'md' => '',
                ][$getSize()] }}">
                    {!! __($subtitle) !!}
                </div> 
            @endif
        </div>

        @if ($slot->isNotEmpty())
            {{ $slot }}
        @endif
    </div>
</div>