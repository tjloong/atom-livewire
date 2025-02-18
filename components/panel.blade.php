<atom:html
    :analytics="false"
    :vite="$attributes->get('vite', [])"
    :cdn="$attributes->get('cdn', [])"
    noindex>
    <div class="group/panel min-h-screen">
        <aside
            x-cloak
            x-data="{ show: false }"
            x-bind:data-atom-panel-sidebar-show="show"
            x-on:toggle-sidebar.window="show = !show"
            class="group/panel-sidebar fixed inset-0 z-40 -translate-x-full [&[data-atom-panel-sidebar-show]]:-translate-x-0 transition transform duration-150 lg:w-64 lg:-translate-x-0 lg:[&[data-atom-panel-sidebar-show]]:-translate-x-full"
            data-atom-panel-sidebar>
            <div
                x-on:click="show = false"
                class="bg-zinc-800/20 blur-sm w-full h-full opacity-0 [[data-atom-panel-sidebar-show]>&]:opacity-100 transition-opacity duration-300 delay-100 lg:hidden">
            </div>
            
            <div class="absolute top-0 bottom-0 left-0 w-64 p-4 bg-zinc-50 border-r shadow lg:shadow-none flex flex-col gap-4 overflow-y-auto">
                <div class="h-10 flex items-center px-2">
                    @isset ($brand)
                        {{ $brand }}
                    @else
                        <a href="/app" class="flex items-center gap-2">
                            <atom:logo class="w-10 h-10 shrink-0" logo-sm/>
                            <div class="text-xl tracking-wider">
                                <span class="font-bold">BACK</span><span class="font-light">OFFICE</span>
                            </div>
                        </a>
                    @endisset
                </div>

                @isset ($sidebar)
                    <nav class="{{ collect([
                        'grow',
                        '[&_[data-atom-collapse]>[data-atom-collapse-content]]:ml-5',
                        '[&_[data-atom-collapse]>[data-atom-collapse-content]]:pl-2',
                        '[&_[data-atom-collapse]>[data-atom-collapse-content]]:mb-3',
                        '[&_[data-atom-collapse]>[data-atom-collapse-content]]:border-l',
                    ])->join(' ') }}">
                        {{ $sidebar }}
                    </nav>
                @endisset
            </div>
        </aside>

        @isset ($navbar)
            <nav
                x-data="{ transparent: true }"
                x-on:scroll.window="transparent = document.documentElement.scrollTop <= 20"
                x-on:transparent="transparent = $event.detail"
                x-bind:class="!transparent && 'bg-zinc-50 border-b border-zinc-200'"
                class="fixed z-10 top-0 left-0 lg:left-64 lg:group-has-[[data-atom-panel-sidebar-show]]/panel:left-0 right-0"
                data-atom-panel-navbar>
                <div class="h-16 px-3 flex items-center transition-colors duration-300">
                    <div class="shrink-0">
                        <button
                            type="button"
                            class="w-10 h-10 flex items-center justify-center rounded-lg text-zinc-400 hover:bg-zinc-100"
                            x-on:click="$dispatch('toggle-sidebar')">
                            <x-icon menu size="18"/>
                        </button>
                    </div>

                    <div class="grow">
                        {{ $navbar }}
                    </div>
                </div>
            </nav>
        @endisset

        <div class="relative min-h-dvh lg:ml-64 lg:group-has-[[data-atom-panel-sidebar-show]]/panel:ml-0 transition-all duration-200">
            {{ $slot }}
        </div>

        @stack('sheets')
    </div>

    <atom:alert/>
    <atom:confirm/>
    <atom:toast/>
</atom:html>