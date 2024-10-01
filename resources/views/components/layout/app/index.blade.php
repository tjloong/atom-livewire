<x-layout :noindex="true" :analytics="false" lang>

@isset ($vite)
<x-slot:vite :scripts="$vite->attributes->get('scripts') ?? []"></x-slot:vite>
@endisset

<x-slot:cdn :list="[
    'flatpickr',
    ...(($cdn ?? null)?->attributes?->get('list') ?? []),
]">
@isset($cdn)
{{ $cdn }}
@endisset
</x-slot:cdn>

<div 
    x-cloak 
    x-data="{ nav: null }"
    x-init="$watch('nav', nav => $dispatch('app-layout-nav-changed', nav))"
    class="app-layout min-h-screen h-px">
    @if ($flash = session('flash'))
        <div class="fixed top-0 left-1/2 -translate-x-1/2 z-50 max-w-screen-md w-full flex flex-col gap-3 p-4">
            <x-inform close
                :type="get($flash, 'type')"
                :title="get($flash, 'title')"
                :body="is_string($flash) ? $flash : (get($flash, 'body') ?? get($flash, 'message'))">
            </x-inform>
        </div>
    @endif

    <div
        x-ref="overlay"
        x-show="nav === 'lg'"
        x-transition.opacity.duration.500ms
        x-on:click="nav = null"
        class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 backdrop-blur-sm lg:hidden">
    </div>

    <aside
        x-ref="nav" 
        x-bind:class="{
            'w-80 lg:w-60': nav === 'lg',
            'w-0 lg:w-0': nav === 'hidden',
            'w-0 lg:w-60': !nav,
        }"
        class="app-layout-nav fixed top-0 bottom-0 left-0 z-40 bg-gray-800 overflow-hidden transition-all duration-200">
        <div class="flex flex-col h-full">
            <div class="shrink-0 px-4 h-16 flex items-center">
                @isset ($brand)
                    {{ $brand }}
                @else
                    <a href="/app" class="flex items-center gap-2">
                        <div class="shrink-0">
                            <x-logo class="w-10 h-10" small/>
                        </div>
                        
                        <div x-show="nav === 'lg' || !nav" class="text-white text-xl tracking-wider">
                            <span class="font-bold">BACK</span><span class="font-light">OFFICE</span>
                        </div>
                    </a>
                @endisset
            </div>

            @isset ($nav)
                <div {{ $nav->attributes->class([
                    'grow overflow-y-auto overflow-x-hidden pt-4',
                    $nav->attributes->get('class', 'flex flex-col gap-1'),
                ]) }}>
                    {{ $nav }}
                </div>
            @endisset
        </div>
    </aside>

    <div
        x-bind:class="{
            'pl-0 lg:pl-0': nav === 'hidden',
            {{-- 'pl-0 lg:pl-[64px]': nav === 'sm', --}}
            'pl-0 lg:pl-60': !nav || nav === 'lg',
        }" 
        class="app-layout-container relative w-full h-full flex flex-col transition-all duration-200">
        <header class="app-layout-header shrink-0 bg-white sticky top-0 z-20 border-b flex items-center">
            <div x-on:click="() => {
                if (nav === 'hidden') nav = null
                else nav = 'hidden'
            }" class="shrink-0 p-4 cursor-pointer hidden lg:flex">
                <x-icon name="table-list" class="m-auto"/>
            </div>

            <div x-on:click="() => {
                if (nav === 'hidden' || !nav) nav = 'lg'
            }" class="shrink-0 p-4 flex cursor-pointer lg:hidden">
                <x-icon name="table-list" class="m-auto"/>
            </div>

            <div class="grow">
                @isset($links)
                    {{ $links }}
                @endisset
            </div>

            @auth
                <div class="shrink-0 p-4">   
                    <div x-cloak x-data="{ open: false }">
                        <div
                            x-ref="anchor"
                            x-on:click.stop="open = true" 
                            x-on:click.away="open = false"
                            class="flex items-center gap-2 justify-end cursor-pointer font-medium">
                            <div class="shrink-0 w-8 h-8 rounded-full bg-gray-500 flex items-center justify-center text-sm font-semibold border-4 border-gray-200 text-gray-100">
                                {{ str(user('name'))->substr(0, 1) }}
                            </div>

                            <div class="grow items-center gap-2 w-max max-w-[200px] hidden md:flex">
                                <span class="truncate">
                                    {!! user('name') !!}
                                </span>
                                <div class="shrink-0 w-4 h-4">
                                    <x-icon dropdown/>
                                </div>
                            </div>
                        </div>

                        <div
                            x-show="open"
                            x-anchor.offset.4="$refs.anchor"
                            x-transition.opacity.duration.300
                            class="bg-white border rounded-md shadow-lg max-w-md min-w-[250px] overflow-hidden">
                            @isset($auth) 
                                {{ $auth }}
                            @else
                                <div class="flex flex-col divide-y">
                                    @if (!current_route('app.*')) <x-dropdown.item label="app.label.back-to-app" icon="back" :href="user()->home()"/> @endif
                                    <x-dropdown.item label="app.label.settings" icon="gear" :href="route('app.settings')"/>
                                    <x-dropdown.item label="app.label.logout" icon="logout" :href="route('logout')"/>
                                </div>
                            @endisset
                        </div>
                    </div>
                </div>
            @endauth
        </header>

        <div class="app-layout-body grow bg-gray-50">
            {{ $slot }}
        </div>
    </div>
</div>

<atom:alert/>
<atom:confirm/>
<atom:toast/>
</x-layout>