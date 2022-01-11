<div class="max-w-screen-xl mx-auto p-6">
    <div class="flex flex-col items-center space-y-2 md:flex-row md:justify-between md:space-y-0">
        <div class="grid space-y-1 order-first md:order-last">
            @if ($socials->count() > 0)
                <div class="flex items-center justify-center space-x-2 md:justify-end">
                    @foreach ($socials as $key => $value)
                        <a href="{{ $value }}" class="flex items-center justify-center {{ $dark ? 'text-gray-200' : 'text-gray-500' }}" target="_blank">
                            <x-icon
                                name="{{ $key === 'facebook' ? 'facebook-circle' : $key }}"
                                type="logo"
                            />
                        </a>
                    @endforeach
                </div>
            @endif

            @if ($phone || $email)
                <div class="text-sm md:text-right">
                    @if ($phone)
                        <a href="tel: {{ $phone }}" class="{{ $dark ? 'text-gray-300' : 'text-gray-500' }} font-light">{{ $phone }}</a>
                    @endif

                    @if ($phone && $email)
                        <span class="px-1 {{ $dark ? 'text-gray-300' : 'text-gray-500' }}">|</span>
                    @endif

                    @if ($email)
                        <a href="mailto: {{ $email }}" class="font-semibold {{ $dark ? 'text-gray-300' : 'text-gray-500' }}">{{ $email }}</a>
                    @endif
                </div>
            @endif
        </div>

        <div class="text-sm font-medium {{ $dark ? 'text-gray-200' : 'text-gray-500' }}">
            @if ($company)
                © {{ date('Y') }} {{ $company }}. All rights reserved.
            @endif
        </div>
    </div>
</div>