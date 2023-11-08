<div class="{{ $attributes->get('class', 'flex items-center gap-2') }}" data-sortable-id="{{ $attributes->get('id') }}">
    @if ($hasHandle = $attributes->get('handle'))
        @isset($handle) {{ $handle }}
        @else 
            <div class="handle shrink-0 cursor-move mb-auto p-2">
                <div class="bg-gray-100 rounded-md flex items-center justify-center p-2">
                    <x-icon name="sort" class="text-gray-400 text-sm"/>
                </div>
            </div>
        @endisset
    @endif

    @if ($label = $attributes->get('label'))
        <div class="grow flex flex-col">
            <div class="flex items-center gap-3">
                @if ($href = $attributes->get('href')) 
                    <x-link :label="$label" :href="$href" 
                        {{ $attributes->except('id', 'handle', 'label', 'href', 'badge', 'small') }}/>
                @else 
                    <div class="font-medium" 
                        {{ $attributes->except('id', 'handle', 'label', 'href', 'badge', 'small') }}>
                        {{ tr($label) }}
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
                <div class="text-gray-500">{{ tr($small) }}</div>
            @endif
        </div>
    @else
        <div class="grow">
            {{ $slot }}
        </div>
    @endif
</div>