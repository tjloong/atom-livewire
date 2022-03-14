@if ($attributes->has('separator'))
    <li class="flex items-center justify-center text-gray-500">
        <x-icon name="chevron-right"/>
    </li>

@elseif ($item = $attributes->get('item'))
    <li class="py-2 flex items-center gap-2" itemscope itemprop="itemListElement" itemtype="https://schema.org/ListItem">
        @if ($attributes->has('icon'))
            <x-icon 
                name="{{ $attributes->get('icon') }}" 
                type="{{ $attributes->get('icon-type') ?? 'regular' }}" 
                class="text-gray-400"
                size="xs"
            />
        @endif

        @if ($attributes->has('last'))
            <span itemprop="name" class="text-gray-500 font-medium">{{ str($item['label'])->limit(20) }}</span>
        @else
            <a itemprop="item" href="{{ $item['url'] }}" class="text-gray-800 font-medium">
                <span itemprop="name">{{ str($item['label'])->limit(20) }}</span>
            </a>
        @endif
    </li>

@elseif ($home && $trails)
    <ol
        class="flex items-center gap-2 {{ $attributes->get('class') }}"
        itemscope
        itemtype="https://schema.org/BreadcrumbList"
    >
        @if ($home)
            <x-breadcrumbs :item="$home" icon="house"/>
            @if (count($trails)) <x-breadcrumbs separator/> @endif
        @endif

        @foreach ($trails as $key => $item)
            @if ($key === array_key_last($trails)) 
                <x-breadcrumbs :item="$item" last/>
            @else
                <x-breadcrumbs :item="$item"/>
                <x-breadcrumbs separator/>
            @endif
        @endforeach
    </ol>
@endif
