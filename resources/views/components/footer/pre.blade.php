<div class="max-w-screen-xl mx-auto px-6 py-10">
    <div class="flex gap-10 flex-col justify-between md:flex-row">
        <div class="flex-shrink-0 md:w-1/3">
            <div class="grid gap-4 {{ $color->text }}">
                @isset($logo)
                    {{ $logo }}
                @elseif ($attributes->has('logo'))
                    <x-logo class="{{ $attributes->get('class.logo', 'w-40') }} {{ $dark ? 'brightness-0 invert' : '' }}"/>
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
