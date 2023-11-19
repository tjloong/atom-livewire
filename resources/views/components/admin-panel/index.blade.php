@php
    $flash = collect([
        'info' => session('flash') ?? session('flash-info'),
        'error' => session('flash-error'),
        'warning' => session('flash-warning'),
        'success' => session('flash-success'),
    ])->filter();
@endphp

<div
    x-cloak
    x-data="{ aside: null }"
    class="min-h-screen h-px">
    @if ($flash->count())
        <div class="fixed top-0 left-1/2 -translate-x-1/2 z-50 max-w-screen-md w-full flex flex-col gap-3 p-4">
            @foreach ($flash as $type => $item)
                <x-alert close
                    :type="$type"
                    :title="data_get($item, 'title')"
                    :message="is_string($item) ? $item : data_get($item, 'message')"/>
            @endforeach
        </div>
    @endif

    <div
        x-show="aside === 'lg'"
        x-transition.opacity.duration.500ms
        x-on:click="aside = null"
        class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 backdrop-blur-sm lg:hidden"></div>

    <aside 
        x-ref="aside" 
        x-bind:class="{
            'w-80 lg:w-60': aside === 'lg',
            {{-- 'w-0 lg:w-[64px]': aside === 'sm', --}}
            'w-0 lg:w-0': aside === 'hidden',
            'w-0 lg:w-60': !aside,
        }"
        class="fixed top-0 bottom-0 left-0 z-40 bg-gray-800 overflow-hidden transition-all duration-200">
        <div class="flex flex-col h-full">
            <div class="shrink-0 py-3 px-4">
                @isset($brand)
                    {{ $brand }}
                @else
                    <a href="{{ user()->home() }}" class="flex items-center gap-2">
                        <div class="shrink-0">
                            <x-logo class="w-10 h-10" small/>
                        </div>
                        
                        <div x-show="aside === 'lg' || !aside" class="text-white text-xl tracking-wider">
                            <span class="font-bold">ATOM</span><span class="font-light">CMS</span>
                        </div>
                    </a>
                @endisset
            </div>

            {{ $aside }}
        </div>
    </aside>

    <div
        x-bind:class="{
            'pl-0 lg:pl-0': aside === 'hidden',
            {{-- 'pl-0 lg:pl-[64px]': aside === 'sm', --}}
            'pl-0 lg:pl-60': !aside || aside === 'lg',
        }" 
        class="w-full h-full flex flex-col transition-all duration-200">
        <header class="shrink-0 bg-white sticky top-0 z-20 border-b flex items-center">
            <div x-on:click="() => {
                if (aside === 'hidden') aside = null
                else aside = 'hidden'
            }" class="shrink-0 p-4 flex cursor-pointer hidden lg:block">
                <x-icon name="table-list" class="m-auto"/>
            </div>

            <div x-on:click="() => {
                if (aside === 'hidden' || !aside) aside = 'lg'
            }" class="shrink-0 p-4 flex cursor-pointer lg:hidden">
                <x-icon name="table-list" class="m-auto"/>
            </div>

            <div class="grow">
                @isset($links) {{ $links }} @endisset
            </div>

            @auth
                <div class="shrink-0 p-4">
                    <x-dropdown>
                        <x-slot:anchor>
                            <div class="flex items-center gap-2 justify-end cursor-pointer font-medium w-24 lg:w-40">
                                <x-icon name="circle-user" class="text-lg"/>
                                <span class="truncate">{!! user('name') !!}</span>
                                <x-icon name="dropdown-caret" class="hidden lg:block"/>
                            </div>
                        </x-slot:anchor>

                        @isset($auth) {{ $auth }}
                        @else
                            <div class="flex flex-col divide-y">
                                @if (!current_route('app.*')) <x-dropdown.item label="layout.nav.back-to-app" icon="back" :href="user()->home()"/> @endif
                                <x-dropdown.item label="layout.nav.settings" icon="gear" :href="route('app.settings')"/>
                                <x-dropdown.item label="layout.nav.logout" icon="logout" :href="route('logout')"/>
                            </div>
                        @endisset
                    </x-dropdown>
                </div>
            @endauth
        </header>

        <div class="grow bg-slate-50">
            {{ $slot }}
        </div>
    </div>
</div>
