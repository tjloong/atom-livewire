<x-form.field {{ $attributes }}>
    <div class="bg-slate-100 rounded-lg flex flex-col divide-y">
        @if (($data = $attributes->get('data')) && count($data))
            <table>
                <thead>
                    @foreach (collect($data)->first() as $th)
                        @if ($name = data_get($th, 'name'))
                            <th class="text-sm text-left font-medium text-gray-400 py-2 px-4 {{
                                data_get($th, 'th_class') ?? data_get($th, 'class') ?? null
                            }}">{{ $name }}</th>
                        @elseif (data_get($th, 'actions'))
                            <th width="50"></th>
                        @endif
                    @endforeach
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr class="border-t">
                            @foreach ($item as $td)
                                <td class="text-sm py-2 px-4 {{ 
                                    data_get($td, 'td_class') ?? data_get($td, 'class') ?? null
                                }}">
                                    @if ($close = data_get($td, 'close'))
                                        @php $color = data_get($close, 'color') @endphp
                                        @php $wireClick = data_get($close, 'wire:click') @endphp
                                        @php $onClick = data_get($close, 'x-on:click') @endphp

                                        <td>
                                            @if ($wireClick) 
                                                <x-close :color="$color" x-on:click="$wire.call('{{ $wireClick[0] }}', {{ json_encode($wireClick[1] ?? null) }})"/>
                                            @elseif ($onClick)
                                                <x-close :color="$color" x-on:click="{{ $onClick }}"/>
                                            @endif
                                        </td>
                                    @else
                                        @php $label = data_get($td, 'label') @endphp
                                        @php $amount = data_get($td, 'amount') @endphp
                                        @php $currency = data_get($td, 'currency') @endphp
                                        @php $href = data_get($td, 'href') @endphp
                                        @php $emitTo = data_get($td, 'emitTo') @endphp
                                        @php $wireClick = data_get($td, 'wire:click') @endphp
                                        @php $onClick = data_get($td, 'x-on:click') @endphp
                                        @php $tdvalue = null @endphp

                                        @if (!empty($label)) @php $tdvalue = $label @endphp
                                        @elseif (is_numeric($amount)) @php $tdvalue = currency($amount, $currency) @endphp
                                        @endif
                                
                                        @if ($href)
                                            <a href="{{ $href }}">{{ $tdvalue }}</a>
                                        @elseif ($emitTo)
                                            <a wire:click="$emitTo(@js($emitTo[0]), @js($emitTo[1]), @js($emitTo[2]))">{{ $tdvalue }}</a>
                                        @elseif ($wireClick)
                                            <a wire:click="{{ $wireClick }}">{{ $tdvalue }}</a>
                                        @elseif ($onClick)
                                            <a x-on:click="{{ $onClick }}">{{ $tdvalue }}</a>
                                        @else
                                            {{ $tdvalue }}
                                        @endif
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $slot }}
        @else
            {{ $slot }}
        @endif

        @isset($button)
            @php $icon = $button->attributes->get('icon') @endphp
            @php $label = $button->attributes->get('label') @endphp
            <a 
                class="p-4 flex items-center justify-center gap-2 text-sm"
                {{ $button->attributes->except(['icon', 'label']) }}
            >
                @if ($icon !== false) <x-icon :name="$icon ?? $label" size="12"/> @endif
                @if ($label) {{ __($label) }}
                @else {{ $button }}
                @endif
            </a>
        @endisset
    </div>
</x-form.field>
