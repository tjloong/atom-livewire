@props([
    'type' => $attributes->get('type', 'info'),
    'errors' => $attributes->get('errors'),
    'title' => $attributes->get('title'),

    'icon' => [
        'neutral' => 'circle-info',
        'info' => 'circle-info',
        'error' => 'circle-xmark',
        'success' => 'circle-check',
        'warning' => 'circle-exclamation',
    ],
    
    'color' => [
        'bg' => [
            'neutral' => 'bg-gray-100',
            'info' => 'bg-blue-100',
            'error' => 'bg-red-100',
            'success' => 'bg-green-100',
            'warning' => 'bg-yellow-100',
        ],
        'title' => [
            'neutral' => 'text-gray-500',
            'info' => 'text-blue-800',
            'error' => 'text-red-800',
            'success' => 'text-green-800',
            'warning' => 'text-yellow-800',
        ],
        'icon' => [
            'neutral' => 'text-gray-400',
            'info' => 'text-blue-400',
            'error' => 'text-red-400',
            'success' => 'text-green-400',
            'warning' => 'text-orange-500',            
        ],
        'text' => [
            'neutral' => 'text-gray-400',
            'info' => 'text-blue-600',
            'error' => 'text-red-600',
            'success' => 'text-green-600',
            'warning' => 'text-orange-700',
        ],
        'border' => [
            'neutral' => 'border border-gray-300',
            'info' => 'border border-blue-300',
            'error' => 'border border-red-300',
            'success' => 'border border-green-300',
            'warning' => 'border border-orange-300',
        ],
    ],
])

<div {{ $attributes->class([
    'p-4 rounded-md', 
    data_get($color, 'bg.'.($errors->all() ? 'error' : $type)), 
    data_get($color, 'border.'.($errors->all() ? 'error' : $type)),
])->except('errors') }}>
    @if ($errors->all())
        <div class="grid gap-1">
            @foreach ($errors->all() as $error)
                <div class="flex items-center gap-2">
                    <x-icon 
                        :name="data_get($icon, $type)" 
                        class="{{ data_get($color, 'icon.error') }} shrink-0" 
                        size="20"
                    />
                    <div class="{{ data_get($color, 'text.error') }} font-medium">
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