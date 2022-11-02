<th 
    class="py-1 px-2 bg-gray-100 font-medium text-sm border-b border-gray-200 leading-6 tracking-wider"
    {{ $attributes->except(['sort', 'label']) }}
>
    @if ($attributes->get('checkbox'))
        <div
            x-data="{
                checked: false,
            }"
            x-on:click="$wire.toggleCheckbox({ name: uid, value: '*' })"
            x-on:table-checkboxes-changed.window="checked = JSON.stringify($event.detail) === JSON.stringify(['*'])"
            x-bind:class="checked ? 'bg-theme ring-theme' : 'bg-white ring-gray-300'"
            class="mx-4 w-4 h-4 rounded ring-2 ring-offset-2 shadow"
        ></div>

    @elseif ($sort = $attributes->get('sort'))
        <div 
            x-data="{
                field: @js($attributes->get('sort')),
                get sorted () {
                    return this.$wire.get('sortBy') === this.field
                },
                sort () {
                    if (this.$wire.get('sortBy') === this.field) {
                        this.$wire.set('sortOrder', this.$wire.get('sortOrder') === 'asc' ? 'desc' : 'asc')
                    }
                    else this.$wire.set('sortOrder', 'asc')
    
                    this.$wire.set('sortBy', this.field)
                },    
            }"
            x-bind:class="sorted ? 'bg-gray-200 rounded px-3' : 'px-2'"
            class="flex items-center gap-1 py-1"
        >
            <a x-on:click.prevent="sort()" {{ $attributes->class(['grow', 'text-left', 'text-gray-600', 'whitespace-nowrap']) }}">
                @if ($label = $attributes->get('label')) {{ __(strtoupper($label)) }}
                @else {{ $slot }}
                @endif
            </a>
            <x-icon x-show="sorted && $wire.get('sortOrder') === 'desc'" name="chevron-up" size="10px" class="shrink-0"/>
            <x-icon x-show="sorted && $wire.get('sortOrder') === 'asc'" name="chevron-down" size="10px" class="shrink-0"/>
        </div>
    @else
        <div {{ $attributes->class(['text-gray-500 py-1 px-2', 'text-left', 'whitespace-nowrap']) }}">
            @if ($label = $attributes->get('label')) {{ __(strtoupper($label)) }}
            @else {{ $slot }}
            @endif
        </div>
    @endif
</th>
