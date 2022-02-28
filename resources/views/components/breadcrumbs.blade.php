@if ($attributes->has('separator'))
    <li class="flex items-center justify-center text-gray-500">
        <x-icon name="chevron-right" size="20px"/>
    </li>

@elseif ($item = $attributes->get('item'))
    <li class="py-2 flex items-center gap-2" itemscope itemprop="itemListElement" itemtype="https://schema.org/ListItem">
        @if ($attributes->has('icon'))
            <x-icon 
                name="{{ $attributes->get('icon') }}" 
                type="{{ $attributes->get('icon-type') ?? 'regular' }}" 
                size="18px" 
                class="text-gray-400"
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
        class="flex items-center gap-2 text-sm"
        itemscope
        itemtype="https://schema.org/BreadcrumbList"
    >
        @if ($home)
            <x-breadcrumbs :item="$home" icon="home" icon-type="solid"/>
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
