@php
    $label = $attributes->get('label');
    $sortBy = $attributes->get('sort');
    $checkbox = $attributes->get('checkbox', false);
@endphp

@if ($attributes->get('menu'))
    <th class="bg-slate-100 border-b border-gray-200 w-12"></th>
@elseif ($checkbox)
    <th
        x-data="{
            get rows () {
                return Array.from($el.closest('table').querySelectorAll('[data-table-checkbox]'))
            },
            get isSelectedAll () {
                return this.rows.length === checkboxes.length
            },
            selectAll () {
                if (!this.isSelectedAll && checkboxes.length) checkboxes = []
                this.rows.forEach(row => row.dispatchEvent(new CustomEvent('toggle-checkbox', { bubble: false })))
            },
        }"
        class="py-1 px-2 bg-slate-100 border-b border-gray-200 w-10 sticky top-0 z-10">
        <div
            x-on:click.stop="selectAll"
            x-bind:class="isSelectedAll ? 'border-theme border-2' : 'border-gray-300'"
            class="mx-4 w-6 h-6 p-0.5 rounded shadow border bg-white cursor-pointer">
            <div x-show="isSelectedAll" class="w-full h-full bg-theme flex text-white p-px">
                <x-icon name="check" class="text-xs m-auto"/>
            </div>
        </div>
    </th>
@else
    <th class="py-1 px-2 bg-slate-100 font-medium text-sm border-b border-gray-200 leading-6 tracking-wider sticky top-0 z-10">
        @if ($sortBy)
            <div 
                x-data="{
                    get isSorted () {
                        return orderBy === @js($sortBy)
                    },
                    sort () {
                        if (orderDesc === true) orderBy = orderDesc = null
                        else {
                            orderDesc = orderDesc === null ? false : true
                            orderBy = @js($sortBy)
                        }
                    }
                }"
                x-on:click="sort"
                x-bind:class="isSorted && 'bg-gray-200 rounded'"
                class="py-1 px-2 whitespace-nowrap cursor-pointer text-black font-semibold text-left flex items-center gap-2"
                {{ $attributes->except(['label', 'sort']) }}>
                <div class="grow truncate uppercase">
                    {!! tr($label) !!}
                </div>

                <div class="shrink-0 text-gray-500 text-xs">
                    <x-icon x-show="isSorted && orderDesc" name="chevron-up"/> 
                    <x-icon x-show="isSorted && !orderDesc" name="chevron-down"/>
                    <x-icon x-show="!isSorted" name="sort"/>
                </div>
            </div>
        @else
            <div class="py-1 px-2 whitespace-nowrap uppercase text-gray-500 text-left">
                {!! tr($label) !!}
            </div>
        @endif
    </th>
@endif
