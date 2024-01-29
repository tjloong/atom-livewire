@php
    $title = $attributes->get('title', 'app.alert.no-result.title');
    $message = $attributes->get('message') ?? $attributes->get('subtitle') ?? 'app.alert.no-result.message';
    $getSize = function() use ($attributes) {
        if ($size = $attributes->get('size')) return $size;
        if ($attributes->get('sm')) return 'sm';
        if ($attributes->get('xs')) return 'xs';
        return 'md';
    };
@endphp

<div class="flex justify-center {{ [
    'xs' => 'gap-3 py-4',
    'sm' => 'flex-col items-center gap-3 py-8',
    'md' => 'flex-col items-center gap-3 py-8',
][$getSize()] }}">
    @isset($icon) {{ $icon }}
    @elseif ($icon = $attributes->get('icon', 'folder-open'))
        <div class="shrink-0 rounded-full bg-white shadow flex border {{ [
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
                {!! tr($title) !!}
            </div>

            @if ($message)
                <div class="text-gray-400 font-medium {{ [
                    'xs' => 'text-sm',
                    'sm' => 'text-sm',
                    'md' => '',
                ][$getSize()] }}">
                    {!! tr($message) !!}
                </div> 
            @endif
        </div>

        @if ($slot->isNotEmpty())
            {{ $slot }}
        @endif
    </div>
</div>