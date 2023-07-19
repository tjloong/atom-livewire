@props([
    'title' => __($attributes->get('title', 'No Results')),
    'subtitle' => __($attributes->get('subtitle', 'There is nothing returned from the search')),
    'size' => $attributes->get('size', 'normal'),
    'icon' => $attributes->get('icon', 'folder-open'),
])

@if ($size === 'xs') 
    <div class="flex justify-center gap-3 py-4">
        <div class="w-8 h-8 rounded-full bg-slate-100 shadow flex border">
            <x-icon :name="$icon.' sm'" class="text-gray-400 m-auto"/>
        </div>
        <div class="flex flex-col gap-4">
            <div>
                <div class="font-semibold text-gray-800">{{ __($title) }}</div>
                @if ($subtitle) <div class="text-sm text-gray-400 font-medium">{{ __($subtitle) }}</div> @endif
            </div>

            @if ($slot->isNotEmpty())
                {{ $slot }}
            @endif
        </div>
    </div>
@elseif ($size === 'sm')
    <div class="flex flex-col items-center justify-center gap-3 py-8">
        <div class="w-12 h-12 rounded-full bg-slate-100 shadow flex border">
            <x-icon :name="$icon" class="text-gray-400 m-auto"/>
        </div>
        <div class="flex flex-col gap-4">
            <div class="text-center">
                <div class="font-semibold text-gray-800">{{ __($title) }}</div>
                @if ($subtitle) <div class="text-sm text-gray-400 font-medium">{{ __($subtitle) }}</div> @endif
            </div>

            @if ($slot->isNotEmpty())
                <div class="text-center">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
@else
    <div class="flex flex-col items-center justify-center gap-3 py-8">
        <div class="w-20 h-20 rounded-full bg-slate-100 shadow flex border">
            <x-icon :name="$icon.' xl'" class="text-gray-400 m-auto"/>
        </div>
        <div class="flex flex-col gap-4">
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-800">{{ __($title) }}</div>
                @if ($subtitle) <div class="text-gray-400 font-medium">{{ __($subtitle) }}</div> @endif
            </div>

            @if ($slot->isNotEmpty())
                <div class="text-center">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
@endif
