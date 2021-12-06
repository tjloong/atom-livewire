<x-notify.alert/>
<x-notify.toast/>
<x-notify.confirm/>
<x-fullscreen-loader/>

<div 
    class="min-h-screen bg-gray-50 text-sm" 
    x-data="{ toggled: false, animate: false }"
    @if ($flash)
        x-init="$dispatch('toast', { message: '{{ $flash->message }}', type: '{{ $flash->type }}' })"
    @endif
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
        <div class="flex flex-col">
            <a href="{{ route('dashboard') }}" class="flex-shrink-0 flex items-center py-3 px-5">
                {{ $brand }}
            </a>

            <div class="flex-shrink-0 text-xs text-gray-500 py-2 px-6">
                NAVIGATION
            </div>

            <div class="flex-grow overflow-y-auto overflow-x-hidden">
                @foreach ($navs->where('enabled', true) as $nav)
                    @if ($navdropdown = $nav->dropdown ?? [])
                        <div x-data="{ expand: false, active: {{ $nav->active ? 'true' : 'false' }} }">
                            <a
                                class="ml-2 py-2.5 px-4 rounded-l-md flex items-center text-white"
                                :class="active && 'bg-gray-900 ml-0 pl-6 active'"
                                href="{{ $nav->href ?? 'javascript: void(0)' }}"
                                @click="expand = !expand"
                            >
                                <x-icon name="{{ $nav->icon }}" size="20px" class="mr-2 flex-shrink-0"/>
                                <div class="flex-grow whitespace-nowrap">
                                    <x-icon 
                                        x-bind:name="expand || active ? 'chevron-down' : 'chevron-right'" 
                                        size="20px" 
                                        class="float-right"
                                    />
                                    {{ $nav->label }}
                                </div>
                            </a>
    
                            <div 
                                class="bg-gray-900 text-gray-300 flex flex-col py-1.5"
                                x-show="expand || active"
                                @click.away="expand = false"
                            >
                                @foreach ($navdropdown as $item)
                                    <a 
                                        href="{{ $item->href }}"
                                        class="py-1.5 pl-7 pr-2 ml-6 rounded-l-md whitespace-nowrap text-white"
                                        :class="active && 'bg-gray-600 border-theme border-r-8 font-medium'"
                                        x-data="{ active: {{ $item->active ? 'true' : 'false' }} }"
                                    >
                                        {{ $item->label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a 
                            href="{{ $nav->href }}"
                            class="ml-2 py-2.5 px-4 rounded-l-md flex items-center text-white" 
                            :class="active && 'bg-gray-600 border-theme border-r-8 font-medium'"
                            x-data="{ active: {{ $nav->active ? 'true' : 'false' }} }"
                        >
                            <x-icon name="{{ $nav->icon }}" size="20px" class="mr-2 flex-shrink-0"/>
                            <div class="flex-grow">
                                {{ $nav->label }}
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
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
        <div class="h-12 bg-white shadow flex items-center sticky top-0 z-10">
            <a class="px-4 py-2 flex-shrink-0 text-gray-900" @click="toggled = !toggled; animate = true">
                <x-icon name="menu"/>
            </a>

            <div class="flex-grow flex items-center">
                @isset($navbarLeft)
                    {{ $navbarLeft }}
                @endisset
            </div>

            <div class="flex-shrink-0 flex items-center">
                @isset($navbarRight)
                    {{ $navbarRight }}
                @endisset

                <div x-data="{ open: false }" x-cloak class="relative px-4">
                    <a class="inline-flex items-center justify-center font-medium text-gray-900 h-12" @click="open = true">
                        <x-icon name="user-circle" class="mr-1.5" /> 
                        <span class="text-sm hidden md:block">
                            {{ Illuminate\Support\Str::limit(auth()->user()->name, 15) }}
                        </span>
                        <x-icon name="chevron-down" class="hidden md:block" />
                    </a>

                    <div 
                        class="absolute right-4 bg-white rounded-md shadow-md border w-max mt-1 py-1.5 font-medium text-sm"
                        x-show="open"
                        x-transition
                        @click.away="open = false"
                    >
                        @foreach ($dropdown as $item)
                            <a href="{{ $item->href }}" class="py-2 px-5 flex items-center text-gray-900 hover:bg-gray-100">
                                <x-icon name="{{ $item->icon }}" size="18px" class="mr-2" /> {{ $item->label }}
                            </a>
                        @endforeach

                        <a href="{{ route('login', ['logout' => 1]) }}" class="py-2 px-5 flex items-center text-gray-900 hover:bg-gray-100">
                            <x-icon name="log-out" size="18px" class="mr-2"/> Logout
                        </a>

                        <div class="py-2 px-5 flex items-center border-t hover:bg-gray-100">
                            <x-icon name="rocket" size="18px" class="mr-2"/> Version {{ $version }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if ($unverified)
            <div class="py-3 px-4 bg-yellow-100 shadow" x-data>
                <div class="md:flex md:items-center md:space-x-2">
                    <x-icon name="error" class="flex-shrink-0 text-yellow-400"/>
                    <div class="flex-grow font-medium text-yellow-600">
                        We have sent a verification link to <span class="font-semibold">{{ request()->user()->email }}</span>, please click on the link to verify it.
                    </div>
                </div>
                <a class="text-xs md:ml-8" x-on:click.prevent="$refs.form.submit()">
                    Resend verification link
                </a>
                <form x-ref="form" method="POST" action="{{ route('verification.send') }}" class="hidden">
                    @csrf
                </form>
            </div> 
        @endif

        <div class="px-5 pt-5 pb-20 md:px-8 md:pt-8">
            {{ $slot }}
        </div>
    </main>
</div>
