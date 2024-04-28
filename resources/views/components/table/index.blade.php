@php
    $sortable = !empty($attributes->wire('sorted')->value());
@endphp

<div 
    x-cloak
    x-data="{
        sortable: @js($sortable),
        orderBy: @entangle('tableOrderBy'),
        orderDesc: @entangle('tableOrderDesc'),
        checkboxes: @entangle('tableCheckboxes').defer,

        createSortable () {
            if (!this.sortable) return

            let tbody = this.$el.querySelector('tbody')
            if (!tbody) return

            new Sortable(tbody, {
                onSort: () => {
                    const rows = Array.from(tbody.querySelectorAll(':scope > tr'))
                    const values = rows.map(tr => (tr.getAttribute('data-sortable-id')))
                    this.$dispatch('sorted', values)
                },
            })
        },

        toggleCheckbox (data) {
            const index = this.checkboxes.indexOf(data)
            if (index > -1) this.checkboxes.splice(index, 1)
            else this.checkboxes.push(data)
        },
    }"
    x-init="createSortable()"
    class="relative flex flex-col divide-y bg-white border shadow rounded-lg"
    {{ $attributes->wire('sorted') }}
    {{ $attributes->wire('key') }}>
    @isset($header) {{ $header }} @endif

    @if ($slot->isEmpty())
        @isset($empty) {{ $empty }}
        @else <x-no-result/>
        @endif
    @else
        <div class="relative w-full overflow-auto rounded-b-lg {{ $attributes->get('class', 'max-h-screen') }}">
            <table class="w-max min-w-full">
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

                @isset($tfoot)
                    <tfoot class="border-t">
                        {{ $tfoot }}
                    </tfoot>
                @endisset
            </table>
        </div>
    @endif
</div>
