@props([
    'bgImg' => $image && $imagePosition === 'bg' ? $image : null,
    'leftImg' => $image && $imagePosition === 'left' ? $image : null,
    'rightImg' => $image && $imagePosition === 'right' ? $image : null,
    'bottomImg' => $image && $imagePosition === 'bottom' ? $image : null,
])

<div {{ $attributes->class(['relative']) }}>
    @if ($bgImg)
        <div class="absolute inset-0">
            <img src="{{ $bgImg }}" class="w-full h-full object-cover opacity-30" width="1200" height="700" alt="{{ $attributes->get('image-alt') }}">
        </div>
    @endif

    <div class="
        max-w-screen-xl mx-auto h-full grid
        {{ $leftImg || $rightImg ? 'gap-6 md:grid-cols-2' : '' }}
    ">
        @if ($leftImg || $rightImg)
            <img 
                src="{{ $leftImg ?? $rightImg }}" 
                class="w-full h-full object-cover {{ $rightImg ? 'order-last' : '' }}" 
                width="1200" 
                height="700" 
                alt="{{ $attributes->get('image-alt') }}"
            >
        @endif

        <div class="
            max-w-screen-lg mx-auto relative h-full flex flex-col justify-center space-y-8 py-16 px-6 md:py-32
            {{ $align === 'center' ? 'text-center' : '' }} 
            {{ $align === 'right' ? 'text-right' : '' }}
        ">
            @isset($title)
                <h1 class="text-3xl font-bold md:text-5xl {{ $text === 'light' ? 'text-gray-300' : 'text-gray-900' }}">
                    {{ $title }}
                </h1>
            @endisset

            @isset($subtitle)
                <h2 class="text-xl font-semibold md:text-2xl {{ $text === 'light' ? 'text-gray-300' : 'text-gray-700' }}">
                    {{ $subtitle }}
                </h2>
            @endisset

            @isset($content)
                <p class="text-lg font-medium md:text-lg {{ $text === 'light' ? 'text-gray-300' : 'text-gray-600' }}">
                    {{ $content }}
                </p>
            @endisset

            @isset($cta)
                <div class="
                    inline-flex items-center space-x-3
                    {{ $align === 'center' ? 'justify-center' : '' }}
                    {{ $align === 'right' ? 'justify-end' : '' }}
                ">
                    {{ $cta }}
                </div>
            @endisset
        </div>

        @if ($bottomImg)
            <img 
                src="{{ $bottomImg }}" 
                class="rounded-lg drop-shadow" 
                width="1200" 
                height="700" 
                alt="{{ $attributes->get('image-alt') }}"
            >
        @endif
    </div>
</div>
