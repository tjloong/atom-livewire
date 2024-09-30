@php
$icon = $attributes->get('icon');
$label = $attributes->get('label');
$route = $attributes->get('route');
$href = $attributes->get('href') ?? ($route ? route($route) : null);
$divider = $attributes->get('divider');

$active = $attributes->has('active') ? $attributes->get('active') : (
    ($href && url()->current() === $href)
    || ($route && current_route($route))
);

$permitted = !$attributes->has('can') || (
    $attributes->has('can')
    && user()
    && user()->can(
        ...(explode(':', $attributes->get('can')))
    )
);

$except = ['icon', 'label', 'route', 'href', 'can', 'active'];
@endphp

@if ($divider && $label)
    <label class="text-sm normal-case text-gray-500 py-2 px-6">@t($label)</label>
@elseif ($divider)
    <div class="h-px bg-gray-100 w-full"></div>
@elseif ($permitted)
    @if ($href)
        <a href="{{ $href }}" class="block pl-2" {{ $attributes->except($except) }}>
            <div class="flex items-center text-white rounded-l-md {{ $active ? 'active font-semibold bg-white/20' : 'font-medium hover:bg-white/10' }}">
                <div class="grow flex items-center gap-2 py-2.5 px-4">
                    @if ($icon)
                        <div x-bind:class="nav === 'sm' && 'text-xl'" class="shrink-0 w-5 h-5 flex">
                            <x-icon :name="$icon" class="m-auto"/>
                        </div>
                    @endif

                    <div x-show="nav === 'lg' || !nav" class="grow leading-none">
                        @t($label)
                    </div>
                </div>

                @if ($active)
                    <div x-show="nav === 'lg' || !nav" class="shrink-0 p-1 px-2">
                        <div class="w-2 h-5 bg-theme rounded-full"></div>
                    </div>
                @endif
            </div>
        </a>
    @elseif ($slot->isNotEmpty())
        <div
            x-data="{ open: false, active: false }"
            x-init="active = $refs.children?.querySelectorAll('.active')?.length > 0">
            <div x-on:click="open = !open" class="pl-2">
                <div
                    x-bind:class="active || open ? 'font-semibold bg-white/20' : 'font-medium hover:bg-white/10'"
                    class="flex items-center gap-2 text-white rounded-l-md py-2.5 px-4 cursor-pointer">
                    <div x-bind:class="nav === 'sm' && 'text-xl'" class="shrink-0 w-5 h-5 flex">
                        <x-icon :name="$icon" class="m-auto"/>
                    </div>

                    <div x-show="nav === 'lg' || !nav" class="grow leading-none">
                        @t($label)
                    </div>

                    <div
                        x-bind:class="(active || open) && 'rotate-90'"
                        class="shrink-0 text-xs transition-transform">
                        <x-icon right/>
                    </div>
                </div>
            </div>

            <div
                x-ref="children"
                x-show="active || open"
                x-on:click.away="open = false" 
                x-collapse
                class="bg-gray-900 text-gray-300 py-1.5 pl-4 flex flex-col gap-1">
                {{ $slot }}
            </div>
        </div>
    @endif
@endif