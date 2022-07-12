<div class="grid gap-4">
    <div 
        x-data="{
            name: @js($uid),
            total: @js($attributes->get('total', 0)),
            checkedValues: [],
            get totalRows () {
                return $el.querySelectorAll('table tbody tr').length
            },
            get checkedCount () {
                if (this.checkedValues.includes('all')) return this.totalRows
                else if (this.checkedValues.includes('everything')) return this.total
                else return this.checkedValues.length
            },
            selectTotal () {
                const data = { name: this.name, value: 'everything' }
                this.$dispatch('table-checkbox-check', data)
                this.$wire && this.$wire.emit('table-checkbox-check', data)
            },
        }"
        x-on:table-checkbox-checked.window="checkedValues = $event.detail"
        class="relative shadow rounded-lg border w-full bg-white overflow-hidden"
    >
        @if (isset($header))
            <div class="p-4 font-bold text-lg border-b">
                {{ $header }}
            </div>
        @elseif ($header = $attributes->get('header'))
            <div class="p-4 font-bold text-lg border-b">
                {{ __($header) }}
            </div>
        @endisset

        <div class="py-3 px-4 flex flex-wrap justify-between items-center gap-2 border-b">
            <div class="text-gray-800 flex items-end gap-1.5">
                @if ($attributes->has('total'))
                    <div class="text-lg font-medium leading-snug">{{ $attributes->get('total') }}</div>
                    <div class="text-gray-500">{{ __('total rows') }}</div>
                @endif
            </div>

            <div x-show="!checkedCount" class="flex flex-wrap items-center gap-2">
                @if ($search = $attributes->get('search') ?? true)
                    <div class="w-60 rounded-md border bg-gray-100 shadow flex items-center gap-2 px-3 py-0.5">
                        <x-icon name="search" class="text-gray-500" size="xs"/>
                        <div class="flex-grow">
                            <input 
                                type="text"
                                wire:model.debounce.500ms="filters.search"
                                class="w-full bg-transparent appearance-none border-0 p-0 focus:ring-0"
                                placeholder="Search"
                            >
                        </div>
                        <a 
                            x-data
                            x-show="$wire.get('filters.search')" 
                            x-on:click.prevent="$wire.set('filters.search', null)" 
                            class="flex items-center justify-center text-gray-800"
                        >
                            <x-icon name="x" size="xs"/>
                        </a>
                    </div>
                @endif

                @if ($attributes->get('total'))
                    @if ($export = $attributes->get('export'))
                        @if (is_array($export))
                            <x-dropdown right>
                                <x-slot:anchor>
                                    <div class="p-1.5 rounded-md flex items-center justify-center text-gray-900 hover:bg-gray-100 hover:border hover:shadow">
                                        <x-icon name="download" size="18px"/>
                                    </div>
                                </x-slot:anchor>

                                @foreach ($export as $val)
                                    <x-dropdown.item
                                        :label="data_get($val, 'label')"
                                        wire:click="export('{{ data_get($val, 'value') }}')"
                                    />
                                @endforeach
                            </x-dropdown>
                        @else
                            <a
                                x-data
                                x-tooltip="Export"
                                wire:click.prevent="export"
                                class="p-1.5 rounded-md flex items-center justify-center text-gray-900 hover:bg-gray-100 hover:border hover:shadow"
                            >
                                <x-icon name="download" size="18px" />
                            </a>
                        @endif
                    @endif
                    
                @endif
                    
                @isset($filters)
                    <a
                        x-data
                        x-tooltip="Filters"
                        x-on:click.prevent="$dispatch('{{ $uid }}-drawer-open')"
                        class="p-1.5 rounded-md flex items-center justify-center text-gray-900 hover:bg-gray-100 hover:border hover:shadow"
                    >
                        <x-icon name="slider" size="18px" />
                    </a>
                @endif
            </div>
        </div>

        <div x-show="checkedCount" class="py-3 px-4 flex items-center justify-between border-b">
            <div class="grid">
                <div class="flex items-center gap-1.5">
                    <div class="font-medium" x-text="checkedCount"></div>
                    <div class="text-gray-500">{{ __('selected rows') }}</div>
                </div>
                <a 
                    x-show="total > totalRows && checkedValues.includes('all')" 
                    x-on:click="selectTotal"
                    class="text-sm"
                >
                    {{ __('Select all :total rows', ['total' => $attributes->get('total')]) }}
                </a>
            </div>

            <div>
                {{ $checked ?? null }}
            </div>
        </div>

        @isset($toolbar)
            <div x-show="!checkedCount" class="py-3 px-4 flex items-center justify-between gap-2 border-b">
                <div>
                    {{ $toolbar }}
                </div>

                @if ($toolbar->attributes->get('trashed'))
                    <x-button color="red" icon="trash-alt" inverted size="sm" x-on:click="$dispatch('confirm', {
                        title: '{{ __('Empty Trashed') }}',
                        message: '{{ __('Are you sure to clear all trashed records?') }}',
                        type: 'warning',
                        onConfirmed: () => $wire.emptyTrashed().then(() => location.reload()),
                    })">
                        Empty Trashed
                    </x-button>
                @endif
            </div>
        @endisset
    
        @if ($attributes->get('total'))
            <div class="w-full overflow-auto">
                <table class="w-max divide-y divide-gray-200 md:w-full md:max-w-full" uid="{{ $uid }}">
                    @isset($head)
                        <thead>
                            <tr>
                                {{ $head }}
                            </tr>
                        </thead>
                    @endisset

                    @isset($body)
                        <tbody class="bg-white">
                            {{ $body }}
                        </tbody>
                    @endisset

                    @isset($foot)
                        <tfoot>
                            {{ $foot }}
                        </tfoot>
                    @endisset
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

        <div class="grid gap-8">
            <div class="grid gap-6">
                {{ $filters }}
            </div>

            <div class="flex items-center justify-between gap-4">
                <x-button color="gray" icon="refresh" wire:click="resetFilters">
                    {{ __('Reset') }}
                </x-button>
            </div>
        </div>
    </x-drawer>
@endisset
