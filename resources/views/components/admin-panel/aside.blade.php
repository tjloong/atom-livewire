@php
    $icon = $attributes->get('icon');
    $label = $attributes->get('label');
    $route = $attributes->get('route');
    $params = $attributes->get('params');
    $href = $attributes->get('href') ?? ($route ? route($route, $params ?? []) : null);
    $permitted = !$attributes->has('can') || ($attributes->has('can') && user() && user()->can($attributes->get('can')));
    $active = $attributes->has('active') ? $attributes->get('active') : (
        collect([
            $href && url()->current() === $href,
            $route && current_route($route),
        ])->filter()->count() > 0
    );

    $except = ['icon', 'label', 'route', 'params', 'href', 'can', 'active'];
@endphp

@if (isset($group) && $group->isNotEmpty())
    <label class="text-sm normal-case text-gray-500 py-2 px-6">{{ tr($label) }}</label>
    {{ $group }}
@elseif ($permitted && (
    (isset($subitems) && $subitems->isNotEmpty())
    || (!isset($subitems))
))
    @if ($href)
        <a href="{{ $href }}" class="block pl-2" {{ $attributes->except($except) }}>
            <div class="flex items-center text-white rounded-l-md {{ $active ? 'active font-semibold bg-white/20' : 'font-medium hover:bg-white/10' }}">
                <div class="grow flex items-center gap-2 py-2.5 px-4">
                    @if ($icon)
                        <div x-bind:class="aside === 'sm' && 'text-xl'" class="shrink-0 w-5 h-5 flex">
                            <x-icon :name="$icon" class="m-auto"/>
                        </div>
                    @endif

                    <div x-show="aside === 'lg' || !aside" class="grow leading-none">
                        {{ tr($label) }}
                    </div>
                </div>

                @if ($active)
                    <div x-show="aside === 'lg' || !aside" class="shrink-0 p-1 px-2">
                        <div class="w-2 h-5 bg-theme rounded-full"></div>
                    </div>
                @endif
            </div>
        </a>
    @elseif (isset($subitems) && $subitems->isNotEmpty())
        <div x-data="{ open: false, active: false }"
            x-init="active = $refs.subitems?.querySelectorAll('.active')?.length > 0">
            <div x-on:click="open = !open" class="pl-2">
                <div
                    x-bind:class="active || open ? 'font-semibold bg-white/20' : 'font-medium hover:bg-white/10'"
                    class="flex items-center gap-2 text-white rounded-l-md py-2.5 px-4 cursor-pointer">
                    <div x-bind:class="aside === 'sm' && 'text-xl'" class="shrink-0 w-5 h-5 flex">
                        <x-icon :name="$icon" class="m-auto"/>
                    </div>

                    <div x-show="aside === 'lg' || !aside" class="grow leading-none">
                        {{ tr($label) }}
                    </div>

                    <div class="shrink-0 text-xs">
                        <x-icon name="chevron-right"
                            x-bind:class="(active || open) && 'rotate-90'"
                            class="transition-transform"/>
                    </div>
                </div>
            </div>

            <div
                x-ref="subitems"
                x-show="active || open"
                x-on:click.away="open = false" 
                x-collapse
                class="bg-gray-900 text-gray-300 py-1.5 pl-4 flex flex-col gap-1">
                {{ $subitems }}
            </div>
        </div>
    @else
        <div class="block pl-2 cursor-pointer" {{ $attributes->except($except) }}>
            <div class="flex items-center text-white rounded-l-md {{ $active ? 'active font-semibold bg-white/20' : 'font-medium hover:bg-white/10' }}">
                <div class="grow flex items-center gap-2 py-2.5 px-4">
                    @if ($icon)
                        <div x-bind:class="aside === 'sm' && 'text-xl'" class="shrink-0 w-5 h-5 flex">
                            <x-icon :name="$icon" class="m-auto"/>
                        </div>
                    @endif

                    <div x-show="aside === 'lg' || !aside" class="grow leading-none">
                        {{ tr($label) }}
                    </div>
                </div>

                @if ($active)
                    <div x-show="aside === 'lg' || !aside" class="shrink-0 p-1 px-2">
                        <div class="w-2 h-5 bg-theme rounded-full"></div>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endif