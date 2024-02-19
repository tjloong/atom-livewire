@php
    $breadcrumbs = $attributes->get('breadcrumbs');
@endphp

@if (count($breadcrumbs))
    <ol
        itemscope
        itemtype="https://schema.org/BreadcrumbList"
        {{ $attributes->class([
            'flex flex-wrap items-center gap-2 py-2 md:flex-nowrap overflow-hidden',
            $attributes->get('class', 'mb-5'),
        ])->except('breadcrumbs') }}>
        @foreach ($breadcrumbs as $i => $breadcrumb)
            @php $icon = data_get($breadcrumb, 'icon') @endphp
            @php $href = data_get($breadcrumb, 'href') @endphp
            @php $label = data_get($breadcrumb, 'label') @endphp

            <li 
                itemscope 
                itemprop="itemListElement" 
                itemtype="https://schema.org/ListItem"
                class="shrink-0 flex items-center gap-2 max-w-[100px] md:max-w-none truncate">
                <div class="flex items-center gap-2">
                    @if ($icon) <x-icon :name="$icon" class="text-gray-400 shrink-0"/> @endif

                    @if ($href && $i !== array_key_last($breadcrumbs))
                        <a itemprop="item" href="{{ $href }}" class="text-gray-800 font-medium truncate">
                            <span itemprop="name">{!! tr($label) !!}</span>
                        </a>
                    @else
                        <span itemprop="name" class="text-gray-500 font-medium truncate">
                            {!! tr($label) !!}
                        </span>
                    @endif
                </div>
            </li>

            @if ($i !== array_key_last($breadcrumbs))
                <li class="flex shrink-0">
                    <x-icon name="chevron-right" class="m-auto text-xs text-gray-400"/>
                </li>
            @endif
        @endforeach
    </ol>
@endif
