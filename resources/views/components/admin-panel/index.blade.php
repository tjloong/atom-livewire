<div 
    x-cloak
    x-data="{ 
        toggled: false, 
        animate: false,
        flash: @js($flash),
        init () {
            if (this.flash) this.$dispatch('toast', this.flash)
            document.querySelector('html').style.fontSize = '14px'
        },
    }"
    class="min-h-screen h-px" 
    id="admin-panel">
    <div
        x-ref="void"
        x-on:click="toggled = false"
        x-bind:class="{
            'transition-0 duration-100 ease-in-out': animate,
            'fixed inset-0 z-20 bg-black opacity-80 lg:hidden': toggled,
        }"
        class="opacity-0"
    ></div>

    <aside 
        x-ref="aside"
        class="fixed left-0 top-0 bottom-0 z-20 overflow-hidden bg-gray-800 print:hidden"
        :class="{
            'transition-0 duration-100 ease-in-out': animate,
            'w-56 lg:w-0': toggled,
            'w-0 lg:w-56': !toggled,
        }"
    >
        <div class="flex flex-col h-full">
            <div class="shrink-0 py-3 px-5">
                @isset($brand)
                    {{ $brand }}
                @else    
                    <a href="{{ route('app.dashboard') }}" class="shrink-0 flex items-center gap-2">
                        <x-logo class="w-8 h-8" small/>
                        
                        <div class="text-white text-lg tracking-wider">
                            <span class="font-bold">Atom</span><span class="font-light">CMS</span>
                        </div>
                    </a>
                @endisset
            </div>

            <div class="grow overflow-y-auto overflow-x-hidden">
                <div class="text-sm text-gray-500 py-2 px-6">
                    NAVIGATION
                </div>

                <div class="grid pb-10">
                    {{ $aside }}
                </div>
            </div>

            @isset($asidefoot)
                <div class="shrink-0">
                    <div class="grid pb-2">
                        {{ $asidefoot }}
                    </div>
                </div>
            @endisset

            @if ($version && tier('root'))
                <div class="shrink-0 text-sm uppercase text-white px-6 pb-2 font-medium">
                    V{{ $version }}
                </div>
            @endif
        </div>
    </aside>

    <main
        x-bind:class="{
            'transition-0 duration-100 ease-in-out': animate,
            'lg:pl-0': toggled,
            'lg:pl-56': !toggled,
        }"
        class="w-full h-full flex flex-col"
    >
        <div class="shrink-0 bg-white sticky top-0 z-10 py-1 px-4 border-b">
            <x-navbar>
                <x-slot:logo>
                    <div class="flex items-center gap-4 md:hidden">
                        <div x-on:click="toggled = !toggled" class="shrink-0 border rounded-lg p-2 flex cursor-pointer">
                            <x-icon name="table-list" class="m-auto"/>
                        </div>

                        @isset($logo) {{ $logo }}
                        @else <x-logo class="h-[30px]" small/>
                        @endisset
                    </div>
                </x-slot:logo>
    
                @isset($links)
                    <x-slot:body>
                        {{ $links }}
                    </x-slot:body>
                @endisset
    
                <x-slot:auth>
                    @isset($auth) {{ $auth }}
                    @else <x-navbar.auth/>
                    @endif
                </x-slot:auth>
            </x-navbar>
        </div>

        <div class="shrink-0 bg-white px-4 border-b">
            <x-breadcrumbs class="max-w-screen-xl mx-auto"/>
        </div>

        <div class="grow bg-slate-50">
            {{ $slot }}
        </div>
    </main>
</div>
