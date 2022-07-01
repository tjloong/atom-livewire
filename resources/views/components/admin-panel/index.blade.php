<x-script.alpine/>
<x-notify/>
<x-loader/>

<div 
    x-data="{ 
        toggled: false, 
        animate: false,
        flash: @js($flash),
        init () {
            if (this.flash) this.$dispatch('toast', flash)
            document.querySelector('html').style.fontSize = '14px'
        },
    }"
    class="min-h-screen bg-gray-50 admin-panel" 
>
    <div
        x-ref="void"
        class="opacity-0"
        :class="{
            'transition-0 duration-100 ease-in-out': animate,
            'fixed inset-0 z-20 bg-black opacity-80 lg:hidden': toggled,
        }"
        @click="toggled = false"
    >
    </div>

    <aside 
        x-ref="aside"
        class="fixed left-0 top-0 bottom-0 z-20 overflow-hidden bg-gray-800"
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
                    <a href="{{ route('app.dashboard') }}" class="flex-shrink-0 flex items-center gap-2">
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
        </div>
    </aside>

    <main
        x-ref="container"
        class="w-full"
        :class="{
            'transition-0 duration-100 ease-in-out': animate,
            'lg:pl-0': toggled,
            'lg:pl-56': !toggled,
        }"
    >
        <x-navbar class="bg-white py-3 px-4 shadow" sticky>
            <x-slot:logo>
                @isset($logo)
                    {{ $logo }}
                @else
                    <div class="flex items-center gap-2">
                        <x-logo class="h-[40px] md:hidden" small/>

                        <a class="flex-shrink-0 text-gray-800 flex items-center justify-center" @click="toggled = !toggled; animate = true">
                            <x-icon name="dots-vertical"/>
                        </a>
                    </div>
                @endisset
            </x-slot:logo>

            @isset($links)
                <x-slot:body>
                    {{ $links }}
                </x-slot:body>
            @endisset
        </x-navbar>

        <x-breadcrumbs class="bg-white py-1 px-4 shadow"/>
    
        @if ($unverified)
            <div class="py-3 px-4 bg-yellow-100 shadow" x-data>
                <div class="md:flex md:items-center md:space-x-2">
                    <x-icon name="error" class="flex-shrink-0 text-yellow-400"/>
                    <div class="flex-grow font-medium text-yellow-600">
                        We have sent a verification link to <span class="font-semibold">{{ request()->user()->email }}</span>, please click on the link to verify it.
                    </div>
                </div>
                <a href="{{ route('verification.send') }}" class="text-sm md:ml-8">
                    Resend verification link
                </a>
            </div> 
        @endif

        <div class="px-5 pt-5 pb-20 md:px-8 md:pt-8 overflow-y-auto">
            {{ $slot }}
        </div>
    </main>
</div>
