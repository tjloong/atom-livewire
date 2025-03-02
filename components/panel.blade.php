@php
if (isset($sheetFooter)) {
    \Jiannius\Atom\Atom::sheet()->footer($sheetFooter);
}
@endphp
<atom:html
:analytics="false"
:vite="$attributes->get('vite', [])"
:cdn="$attributes->get('cdn', [])"
noindex>
    <div
    x-cloak
    x-data="{ sidebar: false }"
    class="group/panel min-h-screen">
        <aside
        x-bind:data-atom-panel-sidebar-show="sidebar"
        x-on:toggle-sidebar.window="sidebar = !sidebar"
        data-atom-panel-sidebar
        class="group/panel-sidebar fixed inset-0 z-40 -translate-x-full [&[data-atom-panel-sidebar-show]]:-translate-x-0 transition transform duration-150 lg:w-64 lg:-translate-x-0 lg:[&[data-atom-panel-sidebar-show]]:-translate-x-full">
            <div
            x-on:click="sidebar = false"
            data-atom-panel-overlay
            class="bg-zinc-800/20 blur-sm w-full h-full opacity-0 [[data-atom-panel-sidebar-show]>&]:opacity-100 transition-opacity duration-300 delay-100 lg:hidden">
            </div>
            
            <div class="absolute top-0 bottom-0 left-0 w-64 bg-zinc-50 border-r shadow lg:shadow-none flex flex-col overflow-y-auto">
                <div class="h-20 flex items-center px-4">
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
                        'grow pb-4 px-2',
                        '[&_[data-atom-collapse]>[data-atom-collapse-content]]:ml-5',
                        '[&_[data-atom-collapse]>[data-atom-collapse-content]]:pl-2',
                        '[&_[data-atom-collapse]>[data-atom-collapse-content]]:mb-3',
                        '[&_[data-atom-collapse]>[data-atom-collapse-content]]:border-l',
                    ])->join(' ') }}">
                        {{ $sidebar }}
                    </nav>
                @endisset

                <div class="shrink-0 pb-2 px-2">
                    @isset ($sidebarfoot)
                        {{ $sidebarfoot }}
                    @endisset

                    <atom:menu-item
                    icon="arrow-left"
                    x-on:click="$dispatch('toggle-sidebar')"
                    class="hidden lg:flex">
                        @t('collapse')
                    </atom:menu-item>
                </div>
            </div>
        </aside>

        <div class="absolute z-40 bottom-0 left-0 p-6 lg:hidden lg:group-has-[[data-atom-panel-sidebar-show]]/panel:block lg:group-has-[[data-atom-panel-sidebar-show]]/panel:left-0">
            <button
            type="button"
            x-on:click="$dispatch('toggle-sidebar')"
            class="size-10 flex items-center justify-center bg-primary text-primary-100 rounded-lg shadow-sm">
                <atom:icon menu/>
            </button>
        </div>

        @isset ($navbar)
            <nav
                x-data="{ transparent: true }"
                x-on:scroll.window="transparent = document.documentElement.scrollTop <= 20"
                x-on:transparent="transparent = $event.detail"
                x-bind:class="!transparent && 'bg-zinc-50 border-b border-zinc-200'"
                class="h-20 fixed z-10 top-0 left-0 lg:left-64 lg:group-has-[[data-atom-panel-sidebar-show]]/panel:left-0 right-0"
                data-atom-panel-navbar>
                <div class="px-3 transition-colors duration-300">
                    {{ $navbar }}
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
