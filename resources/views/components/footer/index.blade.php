<div {{ $attributes->class(['max-w-screen-xl mx-auto p-6']) }}>
    @if (data_get($whatsapp, 'number') && data_get($whatsapp, 'bubble'))
        <a 
            href="{{ data_get($whatsapp, 'url') }}"
            class="fixed bottom-8 right-8 w-14 h-14 bg-green-500 rounded-full drop-shadow z-10 flex items-center justify-center"
            target="_blank"
        >
            <x-icon name="whatsapp" type="logo" size="32px" class="text-white"/>
        </a>
    @endif

    @if ($slot->isNotEmpty())
        <div class="{{ $color->text }}">
            {{ $slot }}
        </div>
    @else
        <div class="flex flex-col items-center space-y-2 md:flex-row md:justify-between md:space-y-0">
            <div class="grid space-y-1 order-first md:order-last">
                @if (count($socials) > 0)
                    <div class="flex items-center justify-center space-x-2 md:justify-end">
                        @if (data_get($whatsapp, 'number') && !data_get($whatsapp, 'bubble'))
                            <a
                                href="{{ data_get($whatsapp, 'url') }}"
                                class="flex items-center justify-center {{ $color->text }}"
                                target="_blank"
                            >
                                <x-icon name="whatsapp" type="logo"/>
                            </a>
                        @endif

                        @foreach ($socials as $key => $value)
                            <a href="{{ $value }}" class="flex items-center justify-center {{ $color->text }}" target="_blank">
                                <x-icon :name="$key" type="logo"/>
                            </a>
                        @endforeach
                    </div>
                @endif

                @if (data_get($company, 'phone') || data_get($company, 'email'))
                    <div class="md:text-right">
                        @if ($phone = data_get($company, 'phone'))
                            <a href="tel: {{ $phone }}" class="{{ $color->text }} font-light">{{ $phone }}</a>
                        @endif

                        @if ($phone && data_get($company, 'email'))
                            <span class="px-1 {{ $color->text }}">|</span>
                        @endif

                        @if ($email = data_get($company, 'email'))
                            <a href="mailto: {{ $email }}" class="font-semibold {{ $color->text }}">{{ $email }}</a>
                        @endif
                    </div>
                @endif
            </div>

            <div class="grid gap-1">
                @if ($name = data_get($company, 'name'))
                    <div class="font-medium {{ $color->text }}">
                        © {{ date('Y') }} {{ $name }}. All rights reserved.
                    </div>
                @endif

                @if ($links = $attributes->get('links') ?? $legals ?? null)
                    <div class="flex items-center gap-2">
                        @foreach ($links as $i => $link)
                            @if ($i > 0)
                                <span class="{{ $color->text }}">|</span>
                            @endif

                            <a href="{{ data_get($link, 'href') }}" class="text-sm {{ $color->text }}">
                                {{ data_get($link, 'label') }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
