<div class="{{ $attributes->get('class') }}">
    <div class="
        mx-auto relative py-10 px-6
        {{ $imagePosition === 'top' || $imagePosition === 'bottom' ? 'max-w-screen-lg' : 'max-w-screen-xl' }}
    ">
        @if ($image && ($imagePosition === 'left' || $imagePosition === 'right'))
            <figure class="
                bg-gray-100 rounded-xl drop-shadow relative overflow-hidden -mt-40 mx-auto w-[300px] pt-[450px]
                md:absolute 
                {{ $imagePosition === 'left' 
                    ? 'md:-top-10 md:-bottom-10 md:left-0 md:-mt-0 md:w-[30%]'
                    : 'md:-top-10 md:-bottom-10 md:right-0 md:-mt-0 md:mx-4 md:w-4/12' }}
            ">
                <div class="absolute inset-0">
                    <img src="{{ $image }}" class="w-full h-full object-cover">
                </div>
            </figure>
        @endif

        <div class="
            flex flex-col
            {{ $imagePosition === 'left' ? 'md:ml-[30%] md:px-6' : '' }}
            {{ $imagePosition === 'right' ? 'md:w-8/12 md:pr-10' : '' }}
        ">
            <div class="relative py-4">
                @if ($imagePosition === 'top' || $imagePosition === 'bottom')
                    <div class="absolute top-0 -left-2">
                        <x-icon name="quote-alt-left" type="solid" size="32px" class="text-gray-400 opacity-40"/>
                    </div>
                @else
                    <x-icon name="quote-alt-left" type="solid" size="64px" class="text-gray-400 opacity-40"/>
                @endif
                
                <p class="
                    relative 
                    {{ $text === 'light' ? 'text-gray-300' : 'text-gray-800' }}
                    {{ $align === 'center' ? 'text-center' : '' }}
                    {{ $align === 'right' ? 'text-right' : '' }}
                ">
                    {{ $content }}
                </p>
            </div>

            <div class="
                flex items-center space-x-4 py-4
                {{ $align === 'center' ? 'justify-center' : '' }}
                {{ $align === 'right' ? 'justify-end' : '' }}
                {{ $imagePosition === 'top' ? 'order-first' : '' }}
            ">
                @if ($image && ($imagePosition === 'top' || $imagePosition === 'bottom'))
                    <figure class="w-24 h-24 drop-shadow rounded-full overflow-hidden bg-gray-100">
                        <img 
                            src="{{ $image }}" 
                            class="w-full h-full object-cover"
                            width="150" 
                            height="150" 
                            alt="{{ $attributes->get('image-alt') ?? 'testimonial-avatar' }}"
                        >
                    </figure>
                @endif

                <div class="{{ $align === 'right' ? 'text-right' : '' }}">
                    <div class="font-semibold {{ $text === 'light' ? 'text-gray-300' : 'text-gray-800' }}">
                        {{ $name }}
                    </div>
                    
                    <div class="text-sm text-gray-400 font-medium">
                        @isset($designation)
                            {{ $designation }}<br>
                        @endisset

                        @isset($company)
                            {{ $company }}
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>