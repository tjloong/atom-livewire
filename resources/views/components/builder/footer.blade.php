@if ($attributes->has('pre'))
    <div class="max-w-screen-xl mx-auto px-6 py-10">
        <div class="flex gap-10 flex-col justify-between md:flex-row">
            <div class="flex-shrink-0 md:w-1/3">
                <div class="grid gap-4 {{ $color->text }}">
                    @isset($logo)
                        {{ $logo }}
                    @elseif ($attributes->has('logo'))
                        <x-logo class="w-40 {{ $dark ? 'brightness-0 invert' : '' }}"/>
                    @endif

                    @isset($info)
                        {{ $info }}
                    @else
                        @if (!empty($briefs))
                            <div>
                                {{ nl2br($briefs) }}
                            </div>
                        @endif

                        @if (!empty($company['address']))
                            <address class="not-italic">{{ $company['address'] }}</address>
                        @endif

                        <div class="grid gap-2">
                            @if (!empty($company['phone']))
                                <a href="tel: {{ $company['phone'] }}" class="flex items-center gap-2 font-medium {{ $color->text }}">
                                    <x-icon name="phone" size="xs"/> {{ $company['phone'] }}
                                </a>
                            @endif

                            @if (!empty($company['email']))
                                <a href="mailto: {{ $company['email'] }}" class="flex items-center gap-2 font-medium {{ $color->text }}">
                                    <x-icon name="envelope" size="xs"/> {{ $company['email'] }}
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex-grow">
                {{ $slot }}
            </div>
        </div>
    </div>

@else
    <div class="max-w-screen-xl mx-auto p-6">
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
@endif
