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
        toggleCheckbox (data) {
            const index = this.checkboxes.indexOf(data)
            if (index > -1) this.checkboxes.splice(index, 1)
            else this.checkboxes.push(data)
        },
    }"
    x-init="sortable && new Sortable($el.querySelector('tbody'), { onSort: () => {
        const rows = Array.from($el.querySelectorAll('tbody > tr'))
        const values = rows.map(tr => (tr.getAttribute('data-sortable-id')))
        $dispatch('sorted', values)
    }})"
    class="relative flex flex-col divide-y bg-white border shadow rounded-lg"
    {{ $attributes->wire('sorted') }}>
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
                    <tfoot>
                        {{ $tfoot }}
                    </tfoot>
                @endisset
            </table>
        </div>
    @endif
</div>
