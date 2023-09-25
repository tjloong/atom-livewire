@props([
    'sortable' => !empty($attributes->wire('sorted')->value()),
])

<div 
    id="{{ $attributes->get('id', 'table') }}"
    x-cloak
    x-data="{
        sortable: @js($sortable),
        checkboxes: [],
        get isEmpty () {
            const rows = Array.from($el.querySelectorAll('table > tbody > tr')).length
            return rows <= 0
        },
        init () {
            if (this.sortable) {
                const tbody = this.$el.querySelector('tbody') 
                if (tbody) new Sortable(tbody, { onSort: () => this.sort() })
            }
        },
        sort () {
            const values = Array.from(this.$el.querySelectorAll('tbody > tr'))
                .map(tr => (tr.getAttribute('data-sortable-id')))

            this.$dispatch('sorted', values)
        },
        toggleCheckbox (data) {
            if (data === '*') {
                this.checkboxes = this.checkboxes.length 
                    ? [] 
                    : Array
                        .from($el.querySelectorAll('[data-table-checkbox]'))
                        .map(elem => (elem.getAttribute('data-table-checkbox')))

                this.$wire.set('checkboxes', this.checkboxes)
            }
            else {
                const index = this.checkboxes.indexOf(data)

                if (index > -1) this.checkboxes.splice(index, 1)
                else this.checkboxes.push(data)

                this.$wire.set('checkboxes', this.checkboxes)
            }
        },
    }"
    class="relative flex flex-col divide-y bg-white border shadow rounded-lg"
    {{ $attributes->wire('sorted') }}>
    @isset($header) {{ $header }} @endif

    @if (
        ($attributes->has('data') && !count($attributes->get('data')))
        || (!$attributes->has('data') && $slot->isEmpty())
    )
        @isset($empty) {{ $empty }}
        @else <x-no-result/>
        @endif
    @else
        <div 
            {{ $attributes->class([
                'table-container w-full overflow-auto rounded-b-lg',
                $attributes->get('class', 'max-h-screen'),
            ])->only('class') }}>
            <table class="w-max min-w-full divide-y divide-gray-200">
                @if ($data = $attributes->get('data'))
                    <thead>
                        <tr>
                            @php $cols = collect($data)->first() @endphp
                            @foreach ($cols as $i => $col)
                                @if (data_get($col, 'menu'))
                                    <x-table.th menu/>
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
                            <x-table.tr data-sortable-id="{{
                                data_get(collect($row)->first(), 'sortable_id')
                            }}">
                                @foreach (array_values($row) as $i => $col)
                                    <x-table.td
                                        :checkbox="data_get($col, 'checkbox')"
                                        :label="data_get($col, 'label')"
                                        :date="data_get($col, 'date')"
                                        :datetime="data_get($col, 'datetime')"
                                        :from-now="data_get($col, 'from_now')"
                                        :href="data_get($col, 'href')"
                                        :target="data_get($col, 'target')"
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
                                        :image="data_get($col, 'image')"
                                        :active="data_get($col, 'active')"
                                        :menu="data_get($col, 'menu')"
                                        :limit="data_get($col, 'limit')"
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
