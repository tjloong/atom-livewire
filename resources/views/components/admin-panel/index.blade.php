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
    class="min-h-screen h-px admin-panel" 
>
    @if (session('mask'))
        <div class="fixed top-0 right-0 left-0 h-4 bg-red-500 z-40"></div>
        <div class="fixed top-0 bottom-0 left-0 w-4 bg-red-500 z-40"></div>
        <div class="fixed top-0 bottom-0 right-0 w-4 bg-red-500 z-40"></div>
        <div class="fixed bottom-0 left-0 right-0 h-4 bg-red-500 z-40"></div>
        <div class="fixed top-0 right-0 bg-red-500 z-40 p-4 min-w-[200px] text-center">
            <div class="text-xs text-gray-200 uppercase">Masked As</div>
            <div class="text-sm font-medium text-white">
                {{ user('name') }}<br>{{ user('email') }}
            </div>
            <a href="/login?logout=1" class="flex items-center justify-center gap-2 text-white text-sm">
                <x-icon name="arrow-left" size="12"/> Back
            </a>
        </div>
    @endif

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
        </div>
    </aside>

    <main
        x-ref="container"
        x-bind:class="{
            'transition-0 duration-100 ease-in-out': animate,
            'lg:pl-0': toggled,
            'lg:pl-56': !toggled,
        }"
        class="w-full h-full flex flex-col"
    >
        <x-navbar 
            :config="['sticky' => true]"
            class="shrink-0 bg-white border-b px-4"
        >
            <x-slot:logo>
                @isset($logo)
                    {{ $logo }}
                @else
                    <div class="flex items-center gap-2">
                        <x-logo class="h-[40px] md:hidden" small/>

                        <a 
                            class="flex-shrink-0 text-gray-800 flex items-center justify-center px-2" 
                            x-on:click="toggled = !toggled; animate = true"
                        >
                            <x-icon x-show="toggled" name="square-caret-left" size="18"/>
                            <x-icon x-show="!toggled" name="square-caret-right" size="18"/>
                        </a>
                    </div>
                @endisset
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

        <div class="shrink-0 bg-white px-4 border-b print:hidden">
            <x-breadcrumbs class="max-w-screen-xl mx-auto"/>
        </div>
    
        @if ($unverified)
            <div x-data class="shrink-0 py-3 px-4 bg-yellow-100 print:hidden">
                <div class="flex flex-wrap items-center gap-2">
                    <x-icon name="triangle-exclamation" class="shrink-0 text-yellow-400"/>
                    <div class="font-medium text-yellow-600">
                        {{ __('We have sent a verification link to :email, please click on the link to verify it.', [
                            'email' => user('email'),
                        ]) }}
                    </div>
                    <a href="{{ route('verification.send') }}" class="text-sm">
                        {{ __('Resend verification link') }}
                    </a>
                </div>
            </div> 
        @endif

        <div class="grow bg-gray-50 print:bg-transparent">
            <div class="px-5 pt-5 pb-20 md:px-8 md:pt-8 print:px-0 print:pt-0 print:pb-0 overflow-y-auto h-full">
                {{ $slot }}
            </div>
        </div>
    </main>
</div>
