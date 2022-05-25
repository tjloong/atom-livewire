<nav 
    x-cloak
    x-data="{ show: false }"
    class="{{
        collect([
            ($fixed ? 'fixed top-0 left-0 right-0 z-10' : 'relative'),
            ($sticky ? 'sticky top-0 z-10' : null),
            ($attributes->get('class') ?? 'p-4'),
        ])->filter()->join(' ')
    }}"
>
    <div class="max-w-screen-xl mx-auto grid divide-y">
        <div class="grid gap-4 items-center md:flex">
            <div class="flex justify-between items-center">
                @isset($logo)
                    {{ $logo }}
                @else
                    <a href="/" class="{{ $attributes->get('class.logo') ?? 'max-w-[100px] max-h-[50px] md:max-w-[150px] md:max-h-[75px]' }}">
                        @if ($attributes->get('logo'))
                            <img src="{{ $attributes->get('logo') }}" width="300" height="150" alt="{{ config('app.name') }}" class="w-full h-full object-contain">
                        @else
                            <x-logo class="w-full h-full"/>
                        @endif
                    </a>
                @endisset

                <a x-on:click="show = !show" class="flex items-center justify-center text-gray-400 md:hidden">
                    <x-icon name="menu" size="md"/>
                </a>
            </div>

            <div 
                x-bind:class="!show && 'hidden'" 
                class="grow fixed inset-0 z-40 p-6 md:static md:p-0 md:block"
            >
                <div x-on:click="show = false" class="absolute inset-0 bg-gray-400/50 md:hidden"></div>

                <div class="
                    relative flex flex-col items-center gap-3 bg-white rounded-lg shadow p-4 max-h-[100%] overflow-auto
                    md:static md:bg-transparent md:shadow-none md:p-0 md:max-h-fit md:overflow-visible
                    md:flex-row
                ">
                    <div class="grow">
                        @isset($body)
                            <div {{ $body->attributes->merge(['class' => 'flex flex-col items-center gap-3 md:flex-row']) }}>
                                {{ $body }}
                            </div>
                        @endisset
                    </div>

                    <div class="shrink-0 w-full md:w-auto">
                        @auth
                            @isset($auth)
                                {{ $auth }}
                            @else
                                <x-navbar.dropdown.auth/>
                            @endisset
                        @else
                            @isset($notauth)
                                {{ $notauth }}
                            @else
                                <x-navbar.login/>
                            @endisset
                        @endauth
                    </div>
                </div>

                <a x-on:click="show = false" class="absolute top-4 right-2 block w-8 h-8 md:hidden">
                    <div class="w-full h-full rounded-full bg-white shadow flex">
                        <x-icon name="x" size="20px" class="m-auto"/>
                    </div>
                </a>
            </div>
        </div>
    </div>
</nav>
