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

<div class="p-4 flex flex-col md:flex-row hover:bg-slate-100">
    <div class="grow flex gap-4">
        @if ($image)
            <div class="shrink-0">
                <x-thumbnail :url="$image" size="40"/>
            </div>
        @endif

        <div class="grow flex flex-col gap-2">
            <div>
                @if ($name) <div class="font-medium truncate">{{ $name }}</div> @endif
                @if ($variant) <div class="text-gray-500 truncate">{{ $variant }}</div> @endif
            </div>

            @if ($description)
                <div class="text-gray-500 text-sm">{!! nl2br($description) !!}</div>
            @endif
        </div>
    </div>

    <div class="shrink-0 flex flex-col items-end gap-4">
        <div class="flex flex-col md:flex-row gap-4 text-right">
            @if (is_numeric($qty)) <div class="w-40">{{ $qty }}</div> @endif
            @if (is_numeric($amount)) <div class="w-40">{{ currency($amount) }}</div> @endif
            @if (is_numeric($total)) <div class="w-40">{{ currency($total) }}</div> @endif
        </div>

        @if ($taxes)
            <div class="flex flex-col text-right text-sm text-gray-500">
                @foreach ($taxes as $tax)
                    <div>{{ $tax->label }}: {{ currency($tax->pivot->amount) }}</div>
                @endforeach
            </div>
        @endif

        @if ($category)
            <div class="text-sm text-gray-500">
                {{ $category->locale('name') }}
            </div>
        @endif
    </div>
</div>
