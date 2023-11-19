@php
    $sortable = !empty($attributes->wire('sorted')->value());
@endphp

<div 
    x-cloak
    x-data="{
        sortable: @js($sortable),
        checkboxes: [],
        get isEmpty () {
            const rows = Array.from($el.querySelectorAll('table > tbody > tr')).length
            return rows <= 0
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
