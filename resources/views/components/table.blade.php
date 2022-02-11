@if ($attributes->has('head'))
    @if ($attributes->get('sort'))
        <th x-data="sortableTableHead('{{ $attributes->get('sort') }}')" x-bind:class="{ 'font-medium underline': isSorted() }">
            <a
                {{ $attributes->class([
                    'text-gray-900 flex items-center space-x-1',
                    'justify-end' => $attributes->get('align') === 'right',
                    'justify-start' => $attributes->get('align') === 'left',
                    'justify-center' => $attributes->get('align') === 'center',
                ]) }}
                x-on:click.prevent="sort()"
            >
                <div>{{ $slot }}</div>
                <x-icon
                    x-bind:name="$wire.get('sortOrder') === 'desc' ? 'chevron-up' : 'chevron-down'" 
                    x-show="isSorted()"
                    size="18px"
                />
            </a>
        </th>

    @elseif ($attributes->get('checkbox'))
        <th {{ $attributes->merge(['class' => 'px-3', 'width' => '30']) }}>
            <x-input.checkbox wire:change="checkAll($event.target.checked)"/>
        </th>

    @else
        <th {{ $attributes->class([
            'text-gray-500',
            'text-right' => $attributes->get('align') === 'right',
            'text-center' => $attributes->get('align') === 'center',
        ]) }}>
            {{ $slot }}
        </th>

    @endif

@elseif ($attributes->has('row'))
    <tr class="hover:bg-gray-50">
        {{ $slot }}
    </tr>

@elseif ($attributes->has('cell'))
    <td {{ $attributes }}>
        {{ $slot }}
    </td>

@elseif ($attributes->has('button'))
    <a class="flex items-center justify-center {{ $color }}" {{ $attributes }}>
        <x-icon name="{{ $attributes->get('icon') }}" size="20px"/>
    </a>

@else
    <div class="flex flex-col space-y-4">
        <div class="relative shadow rounded-lg border-b border-gray-200 w-full bg-white overflow-hidden">
            <div class="rounded-t-lg">
                <div class="py-1 px-4 flex flex-wrap justify-between items-center">
                    <div class="my-1">
                    @if ($attributes->get('total'))
                        <div class="text-sm text-gray-800">
                            Total <span class="font-semibold">{{ $attributes->get('total') }}</span> record(s)
                        </div>
                    @endif
                    </div>
        
                    <div class="flex flex-wrap items-center gap-2 my-1">
                    @isset($checked)
                        {{ $checked }}
                    @else
                        @if ($showSearch)
                            <div class="w-60 rounded-md bg-gray-100 drop-shadow flex items-center gap-2 px-2 py-1.5">
                                <x-icon name="search" class="text-gray-500" size="16px"/>
                                <div class="flex-grow">
                                    <input 
                                        type="text"
                                        wire:model.debounce.500ms="filters.search"
                                        class="w-full bg-transparent appearance-none border-0 text-sm p-0 focus:ring-0"
                                        placeholder="Search"
                                    >
                                </div>
                                <a 
                                    x-show="$wire.get('filters.search')" 
                                    x-on:click.prevent="$wire.set('filters.search', null)" 
                                    class="flex items-center justify-center text-gray-800"
                                >
                                    <x-icon name="x" size="16px"/>
                                </a>
                            </div>
                        @endif
        
                        @if ($showExport && $attributes->get('total'))
                            <a
                                x-data
                                x-tooltip="Export"
                                wire:click.prevent="export"
                                class="p-1.5 rounded flex items-center justify-center text-gray-900 hover:bg-gray-100"
                            >
                                <x-icon name="download" size="18px" />
                            </a>
                        @endif
                            
                        @isset($filters)
                            <a
                                x-data
                                x-tooltip="Filters"
                                x-on:click.prevent="$dispatch('{{ $uid }}-drawer-open')"
                                class="p-1.5 rounded flex items-center justify-center text-gray-900 hover:bg-gray-100"
                            >
                                <x-icon name="slider" size="18px" />
                            </a>
                        @endif
                    @endisset
                    </div>
                </div>
            </div>

            @isset($toolbar)
                <div class="border-t py-2 px-4">
                    {{ $toolbar }}
                </div>
            @endisset
        
            @if ($attributes->get('total'))
                <div class="w-full overflow-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            {{ $head }}
                        </thead>
                    
                        <tbody>
                            {{ $body }}
                        </tbody>
                    </table>
                </div>
            @else
                @isset($empty)
                    {{ $empty }}
                @else
                    <x-empty-state/>
                @endisset
            @endif
        </div>
        
        {!! $attributes->get('links') !!}
    </div>

    @isset($filters)
        <x-drawer uid="{{ $uid }}-drawer">
            <x-slot name="title">Filters</x-slot>

            <div class="grid gap-4">
                <div>
                    {{ $filters }}
                </div>

                <div class="flex items-center gap-2">
                    <x-button color="gray" icon="refresh" size="xs" wire:click="resetFilters">
                        Reset Filters
                    </x-button>
                </div>
            </div>
        </x-drawer>
    @endisset

@endif

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('sortableTableHead', (key) => ({
            isSorted () { 
                return this.$wire.get('sortBy') === key
            },

            sort () {
                if (this.$wire.get('sortBy') === key) {
                    this.$wire.set('sortOrder', this.$wire.get('sortOrder') === 'asc' ? 'desc' : 'asc')
                }
                else this.$wire.set('sortOrder', 'asc')

                this.$wire.set('sortBy', key)
            },
        }))
    })
</script>
