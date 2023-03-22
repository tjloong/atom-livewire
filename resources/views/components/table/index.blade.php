@props([
    'uid' => $attributes->get('uid', 'table'),
])

<div 
    id="{{ $uid }}"
    x-cloak
    x-data="{
        uid: @js($uid),
        get isEmpty () {
            const rows = Array.from($el.querySelectorAll('table > tbody > tr')).length
            return rows <= 0
        },
    }"
    class="relative flex flex-col divide-y bg-white border shadow rounded-lg"
>
    @isset($header) {{ $header }} @endif

    @if (
        ($attributes->has('data') && !count($attributes->get('data')))
        || (!$attributes->has('data') && $slot->isEmpty())
    )
        @isset($empty) {{ $empty }}
        @else <x-empty-state/>
        @endif
    @else
        <div {{ $attributes->class([
            'w-full overflow-auto rounded-b-lg',
            $attributes->get('class', 'max-h-screen'),
        ])->only('class') }}>
            <table class="w-max min-w-full divide-y divide-gray-200">
                @if ($data = $attributes->get('data'))
                    <thead>
                        <tr>
                            @php $cols = collect($data)->first() @endphp
                            @foreach ($cols as $i => $col)
                                @if (data_get($col, 'actions'))
                                    <x-table.th actions/>
                                @else
                                    <x-table.th
                                        :label="data_get($col, 'name') ?? data_get($col, 'column_name')"
                                        :sort="data_get($col, 'sort') ?? data_get($col, 'column_sort')"
                                        :checkbox="!empty(data_get($col, 'checkbox'))"
                                        :class="
                                            data_get($col, 'thclass') 
                                                ?? data_get($col, 'column_class') 
                                                ?? data_get($col, 'class') 
                                                ?? ($i === array_key_last($cols) ? 'text-right' : null)
                                        "
                                    />
                                @endif
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="bg-white">
                        @foreach ($data as $row)
                            <x-table.tr>
                                @foreach (array_values($row) as $i => $col)
                                    <x-table.td
                                        :checkbox="data_get($col, 'checkbox')"
                                        :label="data_get($col, 'label')"
                                        :date="data_get($col, 'date')"
                                        :datetime="data_get($col, 'datetime')"
                                        :from-now="data_get($col, 'from_now')"
                                        :href="data_get($col, 'href')"
                                        :amount="data_get($col, 'amount')"
                                        :currency="data_get($col, 'currency')"
                                        :count="data_get($col, 'count')"
                                        :uom="data_get($col, 'uom')"
                                        :percentage="data_get($col, 'percentage')"
                                        :status="data_get($col, 'status')"
                                        :tags="data_get($col, 'tags')"
                                        :small="data_get($col, 'small')"
                                        :avatar="data_get($col, 'avatar')"
                                        :avatar-placeholder="data_get($col, 'avatar-placeholder')"
                                        :active="data_get($col, 'active')"
                                        :actions="data_get($col, 'actions')"
                                        :class="
                                            data_get($col, 'tdclass') 
                                            ?? data_get($col, 'class') 
                                            ?? ($i === array_key_last(array_values($row)) ? 'text-right' : null)
                                        "
                                    >
                                        @if ($html = data_get($col, 'html')) {!! $html !!} @endif
                                    </x-table.td>
                                @endforeach
                            </x-table.tr>
                        @endforeach
                    </tbody>
                @else
                    @isset($thead)
                        <thead>
                            <tr>
                                {{ $thead }}
                            </tr>
                        </thead>
                    @endisset
                    
                    <tbody class="bg-white">
                        {{ $slot }}
                    </tbody>
                @endif

                @isset($tfoot)
                    <tfoot>
                        {{ $tfoot }}
                    </tfoot>
                @endisset
            </table>
        </div>
    @endif
</div>
