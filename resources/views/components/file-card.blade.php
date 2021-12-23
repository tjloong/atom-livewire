<div {{ $attributes->merge(['class' => 'pt-[100%] bg-gray-100 rounded-md drop-shadow relative overflow-hidden']) }}>
    @if ($type === 'youtube')
        <div class="absolute inset-0 flex items-center justify-center">
            <img src="{{ $url }}" class="h-full w-full object-cover">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-4 h-4 bg-white"></div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center text-red-500">
                <x-icon name="youtube" type="logo" size="48px" />
            </div>
        </div>
    @elseif ($type === 'video')
        <div class="absolute inset-0">
            <video class="w-full h-full object-cover">
                <source src="{{ $url }}"/>
            </video>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center">
                    <x-icon name="play" size="28px"/>
                </div>
            </div>
        </div>
    @elseif ($type === 'image')
        <div class="absolute inset-0">
            <img src="{{ $url }}" class="h-full w-full object-cover">
        </div>
    @elseif ($type === 'pdf')
        <div class="absolute inset-0 flex items-center justify-center">
            <x-icon name="file-pdf" type="solid" size="48px"/>
        </div>
    @else
        <div class="absolute inset-0 flex items-center justify-center">
            <x-icon name="file" size="48px"/>
        </div>
    @endif

    {{ $slot }}
</div>
