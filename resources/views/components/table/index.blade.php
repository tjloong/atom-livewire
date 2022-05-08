<div class="grid gap-4">
    <div class="relative shadow rounded-lg border w-full bg-white overflow-hidden grid divide-y">
        @if (isset($header))
            <div class="p-4 font-bold text-lg">
                {{ $header }}
            </div>
        @elseif ($header = $attributes->get('header'))
            <div class="p-4 font-bold text-lg">
                {{ __($header) }}
            </div>
        @endisset

        <div class="py-2 px-4 flex flex-wrap justify-between items-center gap-2">
            <div class="text-gray-800 py-2">
                @if ($total = $attributes->get('total'))
                    Total <span class="font-semibold">{{ $total }}</span> {{ str('record')->plural($total) }}
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2">
            @isset($checked)
                {{ $checked }}
            @else
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

                @if ($attributes->get('total') && $attributes->get('export'))
                    <a
                        x-data
                        x-tooltip="Export"
                        wire:click.prevent="export"
                        class="p-1.5 rounded-md flex items-center justify-center text-gray-900 hover:bg-gray-100 hover:border hover:shadow"
                    >
                        <x-icon name="download" size="18px" />
                    </a>
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
            @endisset
            </div>
        </div>

        @isset($toolbar)
            <div class="py-2 px-4 flex items-center justify-between gap-2">
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
                <table class="min-w-full w-full divide-y divide-gray-200">
                    @isset($head)
                        <thead>
                            {{ $head }}
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
