@props([
    'type' => $attributes->get('type', 'info'),
    'title' => $attributes->get('title'),

    'icon' => [
        'info' => 'circle-info',
        'error' => 'circle-xmark',
        'success' => 'circle-check',
        'warning' => 'circle-exclamation',
    ],
    
    'color' => [
        'bg' => [
            'info' => 'bg-blue-100',
            'error' => 'bg-red-100',
            'success' => 'bg-green-100',
            'warning' => 'bg-yellow-100',
        ],
        'title' => [
            'info' => 'text-blue-800',
            'error' => 'text-red-800',
            'success' => 'text-green-800',
            'warning' => 'text-yellow-800',
        ],
        'icon' => [
            'info' => 'text-blue-400',
            'error' => 'text-red-400',
            'success' => 'text-green-400',
            'warning' => 'text-orange-500',            
        ],
        'text' => [
            'info' => 'text-blue-600',
            'error' => 'text-red-600',
            'success' => 'text-green-600',
            'warning' => 'text-orange-700',
        ],
        'border' => [
            'info' => 'border border-blue-300',
            'error' => 'border border-red-300',
            'success' => 'border border-green-300',
            'warning' => 'border border-orange-300',
        ],
    ],
])

<div {{ $attributes->class([
    'p-4 rounded-md', 
    data_get($color, 'bg.'.$type), 
    data_get($color, 'border.'.$type),
])->except('errors') }}>
    @if ($attributes->get('errors'))
        <div class="grid gap-1">
            @foreach ($attributes->get('errors') as $error)
                <div class="flex items-center gap-2">
                    <x-icon 
                        :name="data_get($icon, $type)" 
                        class="{{ data_get($color, 'icon.'.$type) }} shrink-0" 
                        size="20"
                    />
                    <div class="{{ data_get($color, 'text.'.$type) }} font-medium">
                        {{ __($error) }}
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="flex gap-3 flex-wrap md:flex-nowrap {{ $slot->isEmpty() || !$title ? 'items-center' : '' }}">
            <x-icon 
                :name="data_get($icon, $type)" 
                class="{{ data_get($color, 'icon.'.$type) }} shrink-0" 
                size="20"
            />

            <div class="grow grid gap-2">
                @if ($title)
                    <div class="{{ data_get($color, 'title.'.$type) }} font-semibold text-lg leading-none">
                        {{ __($title) }}
                    </div>
                @endif
    
                @if ($slot->isNotEmpty())
                    <div class="{{ data_get($color, 'text.'.$type) }} font-medium leading-tight">
                        {{ $slot }}
                    </div>
                @endif
            </div>

            @isset($buttons)
                <div class="shrink-0">
                    {{ $buttons }}
                </div>
            @endisset
        </div>
    @endif
</div>