@if ($trails)
    <ol
        itemscope
        itemtype="https://schema.org/BreadcrumbList"
        {{ $attributes->class([
            'flex flex-wrap items-center gap-2 py-2 md:flex-nowrap overflow-hidden',
            $attributes->get('class'),
        ]) }}
    >
        @foreach ($trails as $i => $trail)
            <li 
                itemscope 
                itemprop="itemListElement" 
                itemtype="https://schema.org/ListItem"
                class="shrink-0 flex items-center gap-2 max-w-[100px] md:max-w-none truncate" 
            >
                @if ($i === array_key_first($trails))
                    <x-icon name="house" class="text-gray-400 shrink-0"/>
                @endif

                @if ($label = data_get($trail, 'label'))
                    @if ($i === array_key_last($trails) || !data_get($trail, 'url'))
                        <span itemprop="name" class="text-gray-500 font-medium truncate">
                            {{ str($label)->limit(100) }}
                        </span>
                    @elseif ($href = data_get($trail, 'url'))
                        <a itemprop="item" href="{{ $href }}" class="text-gray-800 font-medium truncate">
                            <span itemprop="name" class="">
                                {{ str($label)->limit(100) }}
                            </span>
                        </a>
                    @endif
                @endif
            </li>

            @if ($i !== array_key_last($trails))
                <li class="flex shrink-0">
                    <x-icon name="chevron-right" size="10" class="m-auto"/>
                </li>
            @endif
        @endforeach
    </ol>
@endif
