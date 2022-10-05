@props([
    'title' => __($attributes->get('title', 'No Results')),
    'subtitle' => __($attributes->get('subtitle', 'There is nothing returned from the search')),
    'size' => $attributes->get('size'),
    'icon' => $attributes->get('icon'),
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-8 px-4 gap-3']) }}>
    @if ($icon !== false)
        <div class="{{ $size === 'sm' ? 'w-12 h-12' : 'w-20 h-20' }} rounded-full bg-slate-100 shadow flex border">
            <x-icon 
                :name="$icon ?? 'folder-open'" 
                :size="$size === 'sm' ? '18px' : '32px'"
                class="text-gray-400 m-auto"
            />
        </div>
    @endif

    <div class="grid gap-4">
        <div class="text-center">
            <div class="font-semibold text-gray-700 {{ $size === 'sm' ? 'text-base' : 'text-lg' }}">
                {{ $title }}
            </div>
        
            <div class="text-gray-400 font-medium text-center {{ $size === 'sm' ? 'text-sm' : 'text-base' }}">
                {{ $subtitle }}
            </div>
        </div>

        @if ($slot->isNotEmpty())
            <div class="text-center">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>

