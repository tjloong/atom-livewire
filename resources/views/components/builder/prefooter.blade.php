@props(['gridCols' => [
    2 => 'lg:grid-cols-2',
    3 => 'lg:grid-cols-3',
    4 => 'lg:grid-cols-4',
    5 => 'lg:grid-cols-5',
]])

<div class="max-w-screen-xl mx-auto px-6 py-10">
    <div class="grid gap-4 {{ $gridCols[$cols] }}">
        <div class="grid gap-2">
            @if ($attributes->get('logo'))
                <div class="w-60">
                    <img 
                        src="{{ $attributes->get('logo') }}" 
                        width="500" 
                        height="200" 
                        class="w-full h-full object-contain {{ $dark ? 'brightness-0 invert' : '' }}"
                    >
                </div>
            @endif

            <div class="grid gap-1">
                @if (!empty($phone) || !empty($email))
                    <div class="flex flex-col">
                        @if (!empty($phone))
                            <a href="tel: {{ $phone }}" class="font-medium {{ $dark ? 'text-gray-100' : '' }}">{{ $phone }}</a>
                        @endif
            
                        @if (!empty($email))
                            <a href="mailto: {{ $email }}" class="font-medium {{ $dark ? 'text-gray-100' : '' }}">{{ $email }}</a>
                        @endif
                    </div>
                @endif
                
                @if (!empty($address))
                    <address class="text-sm {{ $dark ? 'text-gray-100' : '' }}">{{ $address }}</address>
                @endif
            </div>
        </div>

        @foreach (array_keys($links) as $group)
            <div class="grid gap-2">
                <div class="{{ $dark ? 'text-gray-200' : '' }}">{{ $group }}</div>

                @foreach ($links[$group] as $link)
                    <a href="{{ $link['href'] }}" class="font-semibold {{ $dark ? 'text-white' : '' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</div>