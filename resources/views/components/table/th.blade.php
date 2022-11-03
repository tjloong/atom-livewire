@props([
    'label' => $attributes->get('label'),
    'checkbox' => $attributes->get('checkbox', false),
    'sortBy' => $attributes->get('sort'),
])

@if ($checkbox)
    <th class="py-1 px-2 bg-gray-100 border-b border-gray-200 w-10">
        <div
            x-data="{
                checked: false,
            }"
            x-on:click="$wire.toggleCheckbox({ name: uid, value: '*' })"
            x-on:table-checkboxes-changed.window="checked = JSON.stringify($event.detail) === JSON.stringify(['*'])"
            x-bind:class="checked ? 'border-theme border-2' : 'bg-white border-gray-300'"
            class="mx-4 w-6 h-6 p-0.5 rounded shadow border cursor-pointer"
            id="table-checkbox-all"
        >
            <div x-bind:class="checked ? 'block' : 'hidden'" class="bg-theme w-full h-full"></div>
        </div>
    </th>
@else
    <th class="py-1 px-2 bg-gray-100 font-medium text-sm border-b border-gray-200 leading-6 tracking-wider">
        <div 
            x-data="{
                sortBy: @js($sortBy),
                get current () {
                    return {
                        sortBy: this.$wire.get('sortBy'),
                        sortOrder: this.$wire.get('sortOrder'),
                    }
                },
                get isSorted () {
                    return this.sortBy && this.current.sortBy === this.sortBy
                },
                sort () {
                    if (!this.sortBy) return

                    let sortOrder = 'asc'
                    if (this.isSorted) sortOrder = this.current.sortOrder === 'asc' ? 'desc' : 'asc'

                    this.$wire.set('sortBy', this.sortBy)
                    this.$wire.set('sortOrder', sortOrder)
                },
            }"
            x-on:click="sort"
            x-bind:class="isSorted && 'bg-gray-200 rounded'"
            {{ $attributes->class([
                'py-1 px-2 whitespace-nowrap', 
                $sortBy ? 'cursor-pointer text-black font-semibold' : 'text-gray-500',
                $attributes->get('class', 'text-left'),
            ])->except(['label', 'sort']) }}
            id="{{ str()->slug('th-'.$label) }}"
        >
            <span class="inline-flex items-center gap-2">
                {{ __(str()->upper($label)) }}
                @if ($sortBy) 
                    <x-icon x-show="isSorted && current.sortOrder === 'desc'" name="chevron-up" class="shrink-0" size="10"/> 
                    <x-icon x-show="isSorted && current.sortOrder === 'asc'" name="chevron-down" class="shrink-0" size="10"/>
                    <x-icon x-show="!isSorted" name="sort" class="shrink-0" size="10"/>
                @endif
            </span>
        </div>
    </th>
@endif
