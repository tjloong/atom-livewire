@php
$traces = collect($attributes->get('traces', []))
    ->map(fn ($trace) => is_string($trace) ? ['label' => $trace, 'icon' => null, 'href' => null] : $trace)
    ->map(fn ($trace) => (object) $trace)
    ;

$attrs = $attributes->except(['traces']);
@endphp

@if ($slot->isNotEmpty())
    <div class="flex items-center flex-wrap overflow-hidden">
        <div class="grow">
            <atom:breadcrumb :attributes="$attributes"/>
        </div>

        {{ $slot }}
    </div>
@elseif ($traces->count() > 1)
    <ol
    itemscope
    itemtype="https://schema.org/BreadcrumbList"
    {{ $attrs->class(['flex flex-wrap items-center gap-2 py-3 px-6 overflow-hidden']) }}>
        @foreach ($traces as $i => $trace)
            <li
            itemscope 
            itemprop="itemListElement" 
            itemtype="https://schema.org/ListItem"
            class="shrink-0 max-w-40 lg:max-w-64">
                <div class="flex items-center gap-2">
                    @if ($trace->icon)
                        <atom:icon :name="$trace->icon" class="text-zinc-400 shrink-0"/>
                    @elseif ($i === 0)
                        <atom:icon home class="text-zinc-400 shrink-0"/>
                    @endif

                    @if ($trace->href && $i !== $traces->keys()->last())
                        <a itemprop="item" href="{{ $trace->href }}" class="text-zinc-800 font-medium truncate whitespace-nowrap">
                            <span itemprop="name">{{ $trace->label }}</span>
                        </a>
                    @else
                        <span itemprop="name" class="text-zinc-500 font-medium truncate whitespace-nowrap">
                            {{ $trace->label }}
                        </span>
                    @endif

                    @if ($i !== $traces->keys()->last())
                        <atom:icon right class="shrink-0 text-zinc-400"/>
                    @endif
                </div>

                <meta itemprop="position" content="{{ $i + 1 }}" />
            </li>
        @endforeach
    </ol>
@else
    <ol
    wire:ignore
    x-data="breadcrumb()"
    x-show="traces.length > 1"
    x-on:sheet-changed.window="build()"
    data-atom-breadcrumb
    {{ $attrs->class(['flex flex-wrap items-center gap-2 py-3 overflow-hidden first:mb-4']) }}>
        <template x-for="(trace, i) in traces" hidden>
            <li class="shrink-0 max-w-40 lg:max-w-64">
                <div class="flex items-center gap-2 truncate">
                    <atom:icon home x-show="i === 0" class="text-zinc-400 shrink-0"/>

                    <template x-if="i !== traces.lastIndex()" hidden>
                        <div class="flex items-center gap-2 truncate">
                            <a
                            x-text="trace.label"
                            x-on:click="Atom.sheet(trace.name).show()"
                            class="text-zinc-800 font-medium truncate whitespace-nowrap"></a>
                            <atom:icon right class="shrink-0 text-zinc-400"/>
                        </div>
                    </template>

                    <template x-if="i === traces.lastIndex()" hidden>
                        <span x-text="trace.label" class="text-zinc-500 font-medium truncate whitespace-nowrap"></span>
                    </template>
                </div>
            </li>
        </template>
    </ol>
@endif
