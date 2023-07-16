<div data-sortable-id="{{ $attributes->get('id') }}" class="flex gap-3">
    @if ($hasHandle = $attributes->get('handle'))
        @isset($handle) {{ $handle }}
        @else 
            <div class="handle shrink-0 cursor-move py-2 px-4">
                <x-icon name="sort" class="text-gray-400"/>
            </div>
        @endisset
    @endif

    @if ($label = $attributes->get('label'))
        <div class="flex flex-col py-2 {{ $hasHandle ? 'pr-4' : 'px-4' }}">
            <div class="flex items-center gap-3">
                @if ($href = $attributes->get('href')) 
                    <x-link 
                        :label="$label" 
                        :href="$href" 
                        {{ $attributes->except('id', 'handle', 'label', 'href', 'badge', 'small') }}
                    />
                @else 
                    <div class="font-medium" 
                        {{ $attributes->except('id', 'handle', 'label', 'href', 'badge', 'small') }}
                    >
                        {{ __($label) }}
                    </div>
                @endif

                @if (
                    ($badge = $attributes->get('badge'))
                    && (is_string($badge) || is_numeric($badge))
                )
                    <x-badge :label="$badge" color="blue"/>
                @endif
            </div>

            @if ($small = $attributes->get('small'))
                <div class="text-gray-500">{{ __($small) }}</div>
            @endif
        </div>
    @else
        <div {{ $attributes
            ->merge(['class' => 'grow'])
            ->except('id', 'handle', 'label', 'href', 'badge', 'small') 
        }}>
            {{ $slot }}
        </div>
    @endif
</div>