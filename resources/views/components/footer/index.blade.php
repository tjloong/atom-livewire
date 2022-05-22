<div {{ $attributes->class(['max-w-screen-xl mx-auto p-6']) }}>
    @if ($whatsapp && $whatsapp['number'] && $whatsapp['bubble'])
        <a 
            href="{{ $whatsapp['url'] }}"
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
                        @if ($whatsapp && $whatsapp['number'] && !$whatsapp['bubble'])
                            <a
                                href="{{ $whatsapp['url'] }}"
                                class="flex items-center justify-center {{ $color->text }}"
                                target="_blank"
                            >
                                <x-icon name="whatsapp" type="logo"/>
                            </a>
                        @endif

                        @foreach ($socials as $key => $value)
                            <a href="{{ $value }}" class="flex items-center justify-center {{ $color->text }}" target="_blank">
                                <x-icon
                                    name="{{ $key === 'facebook' ? 'facebook-circle' : $key }}"
                                    type="logo"
                                />
                            </a>
                        @endforeach
                    </div>
                @endif

                @if ($company['phone'] || $company['email'])
                    <div class="md:text-right">
                        @if ($company['phone'])
                            <a href="tel: {{ $company['phone'] }}" class="{{ $color->text }} font-light">{{ $company['phone'] }}</a>
                        @endif

                        @if ($company['phone'] && $company['email'])
                            <span class="px-1 {{ $color->text }}">|</span>
                        @endif

                        @if ($company['email'])
                            <a href="mailto: {{ $company['email'] }}" class="font-semibold {{ $color->text }}">{{ $company['email'] }}</a>
                        @endif
                    </div>
                @endif
            </div>

            <div class="grid gap-1">
                @if ($company['name'])
                    <div class="font-medium {{ $color->text }}">
                        © {{ date('Y') }} {{ $company['name'] }}. All rights reserved.
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
