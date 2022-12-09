@if ($value = $attributes->get('checkbox'))
    <td class="align-top py-3 px-2 w-10">
        <div
            x-data="{
                value: @js($value),
                get checkboxes () {
                    return this.$wire.get('checkboxes')
                },
                get checked () {
                    return this.checkboxes.includes('*')
                        || this.checkboxes.includes('**')
                        || this.checkboxes.includes(this.value)
                },
            }"
            x-on:click="$wire.toggleCheckbox(value)"
            x-bind:class="checked ? 'border-theme border-2' : 'bg-white border-gray-300'"
            class="mx-4 w-6 h-6 p-0.5 rounded shadow border cursor-pointer"
            id="{{ str()->slug('table-checkbox-'.$value) }}"
        >
            <div x-bind:class="checked ? 'block' : 'hidden'" class="w-full h-full bg-theme"></div>
        </div>
    </td>
@else
    <td {{ $attributes
        ->class([
            'py-3 px-4 whitespace-nowrap',
            $attributes->has('dropdown') ? 'w-4 align-middle' : 'align-top',
        ])
        ->except(['checkbox', 'status', 'active', 'tags', 'date', 'datetime', 'from-now', 'avatar'])
    }}>
        @if ($status = $attributes->get('status'))
            <x-badge :label="$status"/>
        @elseif (is_bool($attributes->get('active')))
            <x-badge :label="$attributes->get('active') ? 'active' : 'inactive'"/>
        @elseif ($tags = $attributes->get('tags'))
            @if (count($tags))
                <div class="flex items-center gap-2">
                    @foreach (collect($tags)->take(2) as $tag)
                        <div 
                            @if (strlen($tag) > 20) x-tooltip="{{ $tag }}" @endif
                            class="text-xs font-medium bg-slate-100 rounded-md py-0.5 px-2 border"
                        >
                            {{ str($tag)->limit(20) }}
                        </div>
                    @endforeach

                    @if (count($tags) > 2)
                        <div class="text-sm font-medium bg-slate-100 rounded-md py-1 px-2 border">
                            +{{ count($tags) -  2 }}
                        </div>
                    @endif
                </div>
            @else
                --
            @endif
        @elseif ($date = $attributes->get('date'))
            {{ format_date($date) }}
        @elseif ($datetime = $attributes->get('datetime'))
            <div>{{ format_date($datetime) }}</div>
            <div class="text-sm text-gray-500">{{ format_date($datetime, 'time') }}</div>
        @elseif ($fromNow = $attributes->get('from-now'))
            {{ format_date($fromNow, 'human') }}
        @elseif ($attributes->get('avatar') || $attributes->get('avatar-placeholder'))
            <div class="flex items-center gap-3">
                <div class="shrink-0 flex items-center justify-center">
                    <x-thumbnail
                        :url="$attributes->get('avatar')"
                        :placeholder="$attributes->get('avatar-placeholder')"
                        size="36"
                        circle
                        color="random"
                    />
                </div>

                <div class="grid">
                    @if ($href = $attributes->get('href'))
                        <a 
                            href="{{ $href }}" 
                            class="{{ $tooltip ? '' : 'truncate' }}" 
                            target="{{ $attributes->get('target', '_self') }}"
                            @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                        >
                            {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
                        </a>
                    @else
                        <div 
                            class="{{ $tooltip ? '' : 'truncate' }}" 
                            @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                        >
                            {{ $label ?? ($slot->isNotEmpty() ? $slot : null) ?? '--' }}
                        </div>
                    @endif
        
                    @if ($small = $attributes->get('small'))
                        <div class="text-sm text-gray-500 truncate font-medium">
                            {{ $small }}
                        </div>
                    @endif
                </div>
            </div>
        @elseif ($attributes->get('dropdown'))
            <x-dropdown icon="ellipsis-vertical">
                {{ $slot }}
            </x-dropdown>
        @elseif (isset($label) || $slot->isNotEmpty())
            <div class="grid">
                @if ($href = $attributes->get('href'))
                    <a 
                        href="{{ $href }}" 
                        class="{{ $tooltip ? '' : 'truncate' }}" 
                        target="{{ $attributes->get('target', '_self') }}"
                        @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                    >
                        {{ $label ?: (($slot->isNotEmpty() ? $slot : null) ?? '--') }}
                    </a>
                @else
                    <div 
                        class="{{ collect([
                            $tooltip ? null : 'truncate',
                            $attributes->has('wire:click') || $attributes->has('x-on:click') ? 'cursor-pointer font-semibold text-blue-500' : null,
                        ])->filter()->join(' ') }}"
                        @if ($tooltip) x-tooltip="{{ $tooltip }}" @endif
                    >
                        {{ $label ?: (($slot->isNotEmpty() ? $slot : null) ?? '--') }}
                    </div>
                @endif

                @if ($small = $attributes->get('small'))
                    <div class="text-sm text-gray-500 truncate font-medium">
                        {!! $small !!}
                    </div>
                @endif
            </div>
        @endif
    </td>
@endif