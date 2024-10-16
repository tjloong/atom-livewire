@props(['paginate'])

@php
$search = $attributes->get('search', true);

$count = [
    'trashed' => $attributes->get('trashed', 0),
    'archived' => $attributes->get('archived', 0),
    'total' => $paginate?->total() ?? 0,
];

$rows = collect([50, 100, 200, 400, 600])->filter(fn($n) => $n <= get($count, 'total'))->values()->all();
$filtered = collect($this->filters ?? null)->filter()->values()->count();

$attrs = $attributes
    ->class(['group/table rounded-lg bg-white border border-zinc-200 shadow-sm divide-y'])
    ->except(['search'])
    ;
@endphp

<div
    x-data="{
        sort: @entangle('table.sort'),
        checkboxes: @entangle('table.checkboxes').defer,

        get checkables () {
            return Array.from($root.querySelectorAll('table [data-atom-cell-checkbox]'))
        },
    }"
    data-atom-table
    {{ $attrs }}>
    @if ($paginate || $search || isset($filters))
        <div class="relative py-3 px-4 flex flex-wrap justify-between items-center gap-2" data-atom-table-bar>
            <div class="shrink-0 text-gray-800 flex items-center gap-3">
                @if ($total = get($count, 'total'))
                    <div class="font-medium leading-snug">@t('row-count', $total)</div>

                    @if ($rows)
                        <atom:_dropdown>
                            <div class="text-sm text-zinc-400 flex items-center gap-1">
                                <span x-text="$wire.get('table.max')"></span>
                                <span>/ @t('page')</span>
                                <x-icon down/>
                            </div>

                            <atom:menu>
                                @foreach ($rows as $n)
                                    <atom:menu-item wire:click="$set('table.max', {{$n}})">@e($n) / @t('page')</atom:menu-item>
                                @endforeach
                            </atom:menu>
                        </atom:_dropdown>
                    @endif
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-3">
                @if ($search)
                    <div
                        x-data="{ text: '' }"
                        class="flex items-center justify-center gap-2">
                        <x-icon search class="shrink-0 text-zinc-400"/>
                        <input type="text"
                            x-model="text"
                            x-on:keydown.enter.prevent="$wire.set('filters.search', text)"
                            placeholder="@t('search')"
                            x-bind:class="text ? 'w-40' : 'w-14'"
                            class="focus:outline-none focus:w-40 transition-all duration-100">
                        <x-icon close
                            x-show="text"
                            x-on:click="$wire.set('filters.search', null); text = ''"
                            class="shrink-0 text-zinc-400 cursor-pointer">
                        </x-icon>
                    </div>
                @endif

                @if (!get($this->table, 'trashed') && !get($this->table, 'archived'))
                    <div class="flex flex-wrap items-center gap-1">
                        @isset ($filters)
                            <div
                                x-data="{ visible: false, filters: null }"
                                x-init="filters = $wire.get('filters')"
                                x-on:click.away="visible = false">
                                @if ($filtered)
                                    <div class="flex items-center bg-red-100 border border-red-100 text-red-500 font-medium text-sm rounded-md">
                                        <div x-on:click="visible = true" class="pl-2 cursor-pointer">
                                            @t('filter-count', $filtered)
                                        </div>
        
                                        <div x-on:click="$wire.filters = filters" class="shrink-0 px-1 cursor-pointer text-red-400 hover:text-red-600 flex items-center justify-center">
                                            <x-icon close/>
                                        </div>
                                    </div>
                                @else
                                    <atom:_button icon="filter" variant="link" :tooltip="@t('filter')" x-on:click="visible = true"></atom:_button>
                                @endif
        
                                <div
                                    wire:key="filters"
                                    x-show="visible"
                                    x-transition
                                    class="bg-white rounded-lg border shadow-sm p-4 w-80 absolute top-1 right-1 z-10 space-y-4">
                                    <atom:_heading>@t('filter')</atom:_heading>
                                    {{ $filters }}
                                </div>
                            </div>
                        @endisset

                        @isset ($bar)
                            {{ $bar }}
                        @endisset

                        @if (get($count, 'trashed'))
                            <div class="relative">
                                <div class="absolute w-2 h-2 rounded-full bg-red-500 top-1 right-1"></div>
                                <atom:_button icon="delete" variant="link" :tooltip="@t('trashed')" wire:click="$set('table.trashed', true)"/>
                            </div>
                        @endif

                        @if (get($count, 'archived'))
                            <div class="relative">
                                <div class="absolute w-2 h-2 rounded-full bg-red-500 top-1 right-1"></div>
                                <atom:_button icon="archive" variant="link" :tooltip="@t('archived')"/>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @elseif (isset($bar))
        {{ $bar }}
    @endif

    @if (isset($actions) && $actions->isNotEmpty())
        <template x-if="checkboxes.length" hidden>
            <div class="py-3 px-4 flex items-center gap-3" data-atom-table-actions>
                <div class="flex items-center gap-2 text-sm font-medium text-zinc-400">
                    <x-icon double-check/>
                    <div>
                        <span x-text="checkboxes.length"></span> @t('selected')
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    {{ $actions }}
                </div>
            </div>
        </template>
    @elseif (get($this->table, 'archived'))
        <div class="py-3 px-4 text-zinc-400 font-medium flex items-center gap-3">
            <x-icon back class="shrink-0 cursor-pointer" wire:click="$set('table.archived', false)"/>

            <div class="flex items-center gap-2 font-medium">
                <x-icon archive/>
                @t('showing-archived', get($count, 'total'))
            </div>

            <div x-on:click="restoreArchived" class="underline decoration-dashed text-sm text-zinc-800 cursor-pointer">
                @t('restore')
            </div>
        </div>
    @elseif (get($this->table, 'trashed'))
        <div class="py-3 px-4 text-zinc-400 font-medium flex items-center gap-3">
            <x-icon back class="shrink-0 cursor-pointer" wire:click="$set('table.trashed', false)"/>

            <div class="flex items-center gap-2 font-medium">
                <x-icon delete/>
                @t('showing-trashed', get($count, 'total'))
            </div>

            <div
                x-on:click="Atom.confirm({
                    title: 'app.alert.clear-trashed.title',
                    message: 'app.alert.clear-trashed.message',
                }, 'error').then(() => $wire.emptyTrashed())"
                class="underline decoration-dashed text-sm text-zinc-800 cursor-pointer">
                @t('clear')
            </div>
        </div>
    @endif

    <div
        x-bind:class="checkboxes.length && 'rounded-t-none'"
        class="overflow-hidden last:rounded-b-lg rounded-t-lg group-has-[[data-atom-table-bar]]/table:rounded-t-none">
        <div class="overflow-x-auto">
            @if ($paginate && !get($count, 'total'))
                <atom:empty/>
            @else
                <table class="min-w-full table-fixed text-zinc-800 divide-y divide-zinc-800/10">
                    {{ $slot }}
                </table>
            @endif
        </div>
    </div>

    @if ($paginate?->hasPages())
        <div data-atom-table-paginate>
            @e($paginate->links())
        </div>
    @endif
</div>