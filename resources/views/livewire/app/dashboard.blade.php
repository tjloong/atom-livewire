<div class="max-w-screen-xl mx-auto">
    <x-page-header>
        <x-slot:title>
            <div class="flex items-center gap-2">
                <div class="font-bold text-2xl">
                    {{ __('Dashboard') }}
                </div>

                @if ($this->teams->count())
                    <div class="flex items-center gap-2 font-light text-xl">
                        for
                        <x-dropdown :label="
                            data_get($filters, 'team')
                                ? $this->teams->firstWhere('id', data_get($filters, 'team'))->name
                                : 'All Teams'
                        ">
                            @foreach ($this->teams as $team)
                                <x-dropdown.item 
                                    :label="$team->name"
                                    wire:click="$set('filters.team', {{ $team->id }})"
                                />
                            @endforeach
                        </x-dropdown>
                    </div>
                @endif
            </div>
        </x-slot:title>

        <x-form.date-range wire:model="filters.date"/>
    </x-page-header>
    
    <div class="flex flex-col gap-6">
        @foreach (collect($this->sections) as $widgets)
            @php 
                $widgets = collect($widgets)
                    ->map(fn($widget) => data_get($widget, 'col') 
                        ? $widget
                        : array_merge($widget, ['col' => 1]))
                    ->map('collect');

                $cols = $widgets->sum('col')
            @endphp

            @if ($cols)
                <div class="grid gap-6 {{ [
                    1 => 'md:grid-cols-1',
                    2 => 'md:grid-cols-2',
                    3 => 'md:grid-cols-3',
                    4 => 'md:grid-cols-4',
                    5 => 'md:grid-cols-5',
                    6 => 'md:grid-cols-6',
                    7 => 'md:grid-cols-7',
                    8 => 'md:grid-cols-8',
                    9 => 'md:grid-cols-9',
                    10 => 'md:grid-cols-10',
                    11 => 'md:grid-cols-11',
                    12 => 'md:grid-cols-12',
                ][$cols] }}">
                    @foreach ($widgets as $widget)
                        <div class="{{ [
                            1 => 'md:col-span-1',
                            2 => 'md:col-span-2',
                            3 => 'md:col-span-3',
                            4 => 'md:col-span-4',
                            5 => 'md:col-span-5',
                            6 => 'md:col-span-6',
                            7 => 'md:col-span-7',
                            8 => 'md:col-span-8',
                            9 => 'md:col-span-9',
                            10 => 'md:col-span-10',
                            11 => 'md:col-span-11',
                            12 => 'md:col-span-12',
                        ][$widget->get('col')] }}">
                            @if ($widget->get('type') === 'statbox')
                                <x-dashboard.statbox
                                    :title="$widget->get('title')"
                                    :subtitle="$widget->get('subtitle')"
                                    :count="$widget->get('count')"
                                    :amount="$widget->get('amount')"
                                    :currency="$widget->get('currency')"
                                    :percentage="$widget->get('percentage')"
                                    :class="$widget->get('class')"
                                />
                            @elseif ($widget->get('type') === 'chart')
                                <x-dashboard.chart 
                                    :charts="$widget->get('charts')"
                                    wire:key="{{ uniqid() }}"
                                />
                            @elseif ($widget->get('type') === 'list')
                                <x-dashboard.listing
                                    :title="$widget->get('title')"
                                    :subtitle="$widget->get('subtitle')"
                                    :data="$widget->get('data')"
                                />
                            @elseif ($widget->get('type') === 'livewire')
                                @livewire(
                                    atom_lw($widget->get('component')),
                                    $widget->except(['type', 'component', 'col'])->merge(['filters' => $filters])->toArray(),
                                    key(uniqid()),
                                )
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
</div>