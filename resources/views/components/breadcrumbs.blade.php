@if (count($breadcrumbs) > 1)
<ol
    class="flex items-center gap-2 text-sm"
    itemscope
    itemtype="https://schema.org/BreadcrumbList"
>
    @foreach ($breadcrumbs as $key => $item)
        <li 
            class="py-2"
            itemscope
            itemprop="itemListElement"
            itemtype="https://schema.org/ListItem"
        >
            @if ($key === array_key_first($breadcrumbs))
                <a itemprop="item" href="{{ $item['url'] }}" class="flex items-center gap-2 text-gray-800 font-medium">
                    <x-icon name="home" type="solid" size="18px" class="text-gray-400"/>
                    <span itemprop="name">{{ $item['label'] }}</span>
                </a>
            @elseif ($key === array_key_last($breadcrumbs))
                <span itemprop="name" class="text-gray-500 font-medium">{{ $item['label'] }}</span>
            @else
                <a itemprop="item" href="{{ $item['url'] }}" class="text-gray-800 font-medium">
                    <span itemprop="name">{{ $item['label'] }}</span>
                </a>
            @endif
            <meta itemprop="position" content="{{ $key + 1 }}" />
        </li>

        @if ($key !== array_key_last($breadcrumbs))
            <li class="flex items-center justify-center text-gray-500">
                <x-icon name="chevron-right" size="20px"/>
            </li>
        @endif
    @endforeach
</ol>
@endif
