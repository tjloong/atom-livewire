<div class="flex flex-col divide-y">
    <div class="hidden py-3 px-4 md:flex md:gap-4">
        @foreach ([
            'item_name' => 'grow',
            'qty' => 'md:w-48 text-right',
            'price' => 'md:w-48 text-right',
            'total' => 'md:w-48 text-right',
        ] as $key => $class)
            @if ($col = $this->columns->get($key))
                <div class="{{ $class }} text-sm font-medium text-gray-500">
                    {{ __(str()->upper($col)) }}
                </div>
            @endif
        @endforeach
    </div>

    <div class="flex flex-col divide-y">
        @forelse ($this->items as $item)
            <div class="py-3 px-4 flex flex-col md:flex-row gap-4">
                <div class="grow flex flex-col gap-2">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="grow grid gap-2">
                            <div class="font-medium">
                                {{ $item->name }}
                            </div>

                            @if ($this->columns->get('item_description') && $item->description)
                                <div class="text-sm text-gray-500 md:hidden">
                                    {!! nl2br($item->description) !!}
                                </div>
                            @endif    
                        </div>

                        @if ($this->columns->get('qty'))
                            <div class="shrink-0 md:w-48 md:text-right">
                                <div class="text-xs font-medium text-gray-400 md:hidden">
                                    {{ str()->upper($this->columns->get('qty')) }}
                                </div>
                                {{ $item->qty }}
                            </div>
                        @endif

                        @if ($this->columns->get('price'))
                            <div class="shrink-0 md:w-48 md:text-right">
                                <div class="text-xs font-medium text-gray-400 md:hidden">
                                    {{ str()->upper($this->columns->get('price')) }}
                                </div>
                                {{ currency($item->amount) }}
                            </div>
                        @endif
                    </div>

                    @if ($this->columns->get('item_description') && $item->description)
                        <div class="hidden text-sm text-gray-500 md:block">
                            {!! nl2br($item->description) !!}
                        </div>
                    @endif
                </div>

                @if ($this->columns->get('total'))
                    <div class="shrink-0 md:w-48 md:text-right">
                        <div class="grid gap-4">
                            <div>
                                <div class="text-xs font-medium text-gray-400 md:hidden">
                                    {{ str()->upper($this->columns->get('total')) }}
                                </div>
                                {{ currency($item->subtotal, null, false) }}
                            </div>

                            @if (enabled_module('taxes') && $this->columns->has('tax'))
                                @if ($taxes = $item->taxes)
                                    <div class="flex flex-col">
                                        @foreach ($taxes as $tax)
                                            <div class="flex items-center gap-4 text-sm md:justify-end">
                                                <div class="text-gray-500">{{ $tax->label }}</div>
                                                <div>{{ currency($tax->pivot->amount) }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <x-empty-state title="No items" subtitle="This document has no items" size="sm"/>
        @endforelse
    </div>
</div>
