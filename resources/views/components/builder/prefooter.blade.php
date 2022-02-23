<div class="max-w-screen-xl mx-auto px-6 py-10">
    <div class="flex gap-4 flex-col justify-between md:flex-row">
        <div class="flex-shrink-0 md:w-80">
            <div class="grid gap-4">
                @isset($logo)
                    {{ $logo }}
                @elseif ($attributes->has('logo'))
                    <div class="w-40">
                        <img 
                            src="{{ $attributes->get('logo') }}" 
                            width="500" 
                            height="200" 
                            class="w-full h-full object-contain {{ $dark ? 'brightness-0 invert' : '' }}"
                        >
                    </div>
                @endif

                @if (!empty($briefs))
                    <div class="text-sm {{ $dark ? 'text-gray-200' : '' }}">
                        {{ nl2br($briefs) }}
                    </div>
                @endif

                <div class="grid gap-1 text-sm">
                    @if (!empty($phone))
                        <div class="flex items-center gap-2">
                            <x-icon name="phone" size="18px" class="{{ $dark ? 'text-gray-100' : '' }}"/>
                            <a href="tel: {{ $phone }}" class="font-medium {{ $dark ? 'text-gray-100' : '' }}">{{ $phone }}</a>
                        </div>
                    @endif

                    @if (!empty($email))
                        <div class="flex items-center gap-2">
                            <x-icon name="envelope" size="18px" class="{{ $dark ? 'text-gray-100' : '' }}"/>
                            <a href="mailto: {{ $email }}" class="font-medium {{ $dark ? 'text-gray-100' : '' }}">{{ $email }}</a>
                        </div>
                    @endif

                    @if (!empty($address))
                        <div class="flex items-center gap-2">
                            <x-icon name="map" size="18px" class="{{ $dark ? 'text-gray-100' : '' }}"/>
                            <address class="not-italic {{ $dark ? 'text-gray-100' : '' }}">{{ $address }}</address>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{ $slot }}
    </div>
</div>
