@props([
    'image' => $attributes->get('image', false),
    'name' => $attributes->get('name', false),
    'variant' => $attributes->get('variant_name', false),
    'description' => $attributes->get('description', false),
    'qty' => $attributes->get('qty', false),
    'amount' => $attributes->get('amount', false),
    'total' => $attributes->get('subtotal', false),
    'taxes' => $attributes->get('taxes', false),
    'category' => $attributes->get('category', false),
])

@php 
    $columns = array_filter([
        $image !== false || $name !== false || $variant !== false || $description !== false
            ? compact('image', 'name', 'variant', 'description')
            : null,
        
        $qty !== false ? compact('qty') : null,
        $amount !== false ? compact('amount') : null,

        $total !== false || $taxes !== false || $category !== false 
            ? compact('total', 'taxes', 'category') : null,
    ])
@endphp

<div class="p-4 flex flex-col md:flex-row hover:bg-slate-100">
    @foreach ($columns as $i => $col)
        @if ($i === array_key_first($columns))
            <div class="grow flex gap-4">
                @if ($image = data_get($col, 'image'))
                    <div class="shrink-0">
                        <x-thumbnail :url="$image" size="40"/>
                    </div>
                @endif

                <div class="grow flex flex-col">
                    @if ($name = data_get($col, 'name'))
                        <div class="font-medium truncate">{{ $name }}</div>
                    @endif
                    @if ($variant = data_get($col, 'variant'))
                        <div class="text-gray-500 truncate">{{ $variant }}</div>
                    @endif
                    @if ($desc = data_get($col, 'description'))
                        <div class="text-gray-500 text-sm py-2">{!! nl2br($desc) !!}</div>
                    @endif
                </div>
            </div>
        @else
            <div class="shrink-0 flex flex-col gap-2 text-right {{
                $i === array_key_last($columns) ? 'md:w-3/12' : 'md:w-2/12'
            }}">
                @if ($qty = data_get($col, 'qty'))
                    <div>{{ $qty }}</div>
                @endif
                @if ($amount = data_get($col, 'amount'))
                    <div>{{ currency($amount) }}</div>
                @endif
                @if ($total = data_get($col, 'total'))
                    <div>{{ currency($total) }}</div>
                @endif

                @if ($taxes = data_get($col, 'taxes'))
                    @foreach ($taxes as $tax)
                        <div class="text-sm text-gray-500">
                            {{ $tax->label }}: {{ currency($tax->pivot->amount) }}
                        </div>
                    @endforeach
                @endif

                @if ($category = data_get($col, 'category'))
                    <div class="text-sm text-gray-500">
                        {{ $category->locale('name') }}
                    </div>
                @endif
            </div>
        @endif
    @endforeach
</div>
