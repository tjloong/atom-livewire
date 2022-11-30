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
        ($attributes->has('data') && !$attributes->get('data'))
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
                                <x-table.th
                                    :label="data_get($col, 'column_name')"
                                    :sort="data_get($col, 'column_sort')"
                                    :class="data_get($col, 'column_class') ?? (
                                        $i === array_key_last($cols)
                                            ? 'text-right'
                                            : null
                                    )"
                                />
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
                                        :status="data_get($col, 'status')"
                                        :tags="data_get($col, 'tags')"
                                        :small="data_get($col, 'small')"
                                        :avatar="data_get($col, 'avatar')"
                                        :class="data_get($col, 'class') ?? (
                                            $i === array_key_last(array_values($row))
                                                ? 'text-right'
                                                : null
                                        )"
                                    />
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
