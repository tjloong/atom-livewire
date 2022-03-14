<div class="max-w-screen-xl mx-auto px-6 py-10">
    <div class="flex gap-4 flex-col justify-between md:flex-row">
        <div class="flex-shrink-0 md:w-1/3">
            <div class="grid gap-4">
                @isset($logo)
                    {{ $logo }}
                @elseif ($attributes->has('logo'))
                    <div class="w-40">
                        <x-atom-logo class="w-full h-full object-contain {{ $dark ? 'brightness-0 invert' : '' }}"/>
                    </div>
                @endif

                @if (!empty($briefs))
                    <div class="{{ $dark ? 'text-gray-200' : '' }}">
                        {{ nl2br($briefs) }}
                    </div>
                @endif

                <div class="grid gap-2">
                    @if (!empty($phone))
                        <div class="flex items-center gap-2 {{ $dark ? 'text-gray-100' : '' }}">
                            <x-icon name="phone" size="xs"/>
                            <a href="tel: {{ $phone }}" class="font-medium {{ $dark ? 'text-gray-100' : '' }}">{{ $phone }}</a>
                        </div>
                    @endif

                    @if (!empty($email))
                        <div class="flex items-center gap-2 {{ $dark ? 'text-gray-100' : '' }}">
                            <x-icon name="envelope" size="xs"/>
                            <a href="mailto: {{ $email }}" class="font-medium {{ $dark ? 'text-gray-100' : '' }}">{{ $email }}</a>
                        </div>
                    @endif

                    @if (!empty($address))
                        <div class="flex items-center gap-2 {{ $dark ? 'text-gray-100' : '' }}">
                            <x-icon name="location-dot" size="xs"/>
                            <address class="not-italic {{ $dark ? 'text-gray-100' : '' }}">{{ $address }}</address>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex-grow">
            {{ $slot }}
        </div>
    </div>
</div>
