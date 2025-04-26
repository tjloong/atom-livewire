@props(['paginate'])

@php
$search = $attributes->get('search', true);
$paginate = $paginate ?? null;

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
                @if (isset($total) && $total instanceof \Illuminate\View\ComponentSlot)
                    {{ $total }}
                @elseif ($total = get($count, 'total'))
                    <div class="font-medium leading-snug">@t('row-count', $total)</div>

                    @if ($rows)
                        <atom:_dropdown>
                            <div class="text-sm text-muted flex items-center gap-1">
                                <span x-text="$wire.get('table.max')"></span>
                                <span>/ @t('page')</span>
                                <x-icon down/>
                            </div>

                            <x-slot:content>
                                @foreach ($rows as $n)
                                    <atom:menu-item wire:click="$set('table.max', {{$n}})">@e($n) / @t('page')</atom:menu-item>
                                @endforeach
                            </x-slot:content>
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
                        <atom:icon close
                            x-show="text"
                            x-on:click="$wire.set('filters.search', null); text = ''"
                            class="shrink-0 text-zinc-400 cursor-pointer">
                        </atom:icon>
                    </div>
                @endif

                @if (!get($this->table, 'trashed') && !get($this->table, 'archived'))
                    <div @class([
                        'flex items-center gap-1 flex-wrap',
                        '[&_button]:flex [&_button]:items-center [&_button]:justify-center',
                        '[&_button]:rounded [&_button]:p-0.5 [&_button]:mx-1 [&_button]:border-zinc-300',
                        '[&_button]:focus:outline-none [&_button]:focus:border-zinc-400',
                    ])>
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
                                            <atom:icon close/>
                                        </div>
                                    </div>
                                @else
                                    <button type="button" x-tooltip="t('filter')" x-on:click="visible = true">
                                        <atom:icon filter/>
                                    </button>
                                @endif

                                <div
                                    wire:key="filters"
                                    x-show="visible"
                                    x-transition.duration.200
                                    class="absolute top-1 right-1 w-80 z-10">
                                    <atom:menu class="p-5 space-y-6">
                                        {{ $filters }}
                                    </atom:menu>
                                </div>
                            </div>
                        @endisset

                        @isset ($bar)
                            {{ $bar }}
                        @endisset

                        @if (get($count, 'trashed'))
                            <div class="relative">
                                <div class="absolute w-2 h-2 z-1 rounded-full bg-red-500 top-1 right-1"></div>
                                <atom:_button icon="delete" variant="link" :tooltip="@t('trashed')" wire:click="$set('table.trashed', true)"/>
                            </div>
                        @endif

                        @if (get($count, 'archived'))
                            <div class="relative">
                                <div class="absolute w-2 h-2 z-1 rounded-full bg-red-500 top-1 right-1"></div>
                                <atom:_button icon="archive" variant="link" :tooltip="@t('archived')"/>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @elseif (isset($bar))
        <div data-atom-table-bar>
            {{ $bar }}
        </div>
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

            <div x-on:click="$wire.restoreArchived()" class="underline decoration-dashed text-sm text-zinc-800 cursor-pointer">
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
                    title: 'clear-all-trashed',
                    message: 'this-will-permanently-delete-all-selected-records',
                }, 'error').then(() => $wire.set('table.trashed', false)).then(() => $wire.emptyTrashed())"
                class="underline decoration-dashed text-sm text-zinc-800 cursor-pointer">
                @t('empty-trashed')
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

                @isset ($footer)
                    <div class="border-t border-zinc-200 p-3">
                        {{ $footer }}
                    </div>
                @endisset
            @endif
        </div>
    </div>

    @if ($paginate?->hasPages())
        <div class="px-4" data-atom-table-paginate>
            @e($paginate->links())
        </div>
    @endif
</div>
