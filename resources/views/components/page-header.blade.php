<div class="w-full mb-6 flex flex-wrap justify-between">
    <div class="flex-grow flex my-1">
        @if ($back)
            <div class="flex-shrink-0">
                <a 
                    class="bg-gray-300 text-gray-800 rounded py-1.5 px-2 flex items-center justify-center mr-3"
                    @if (request()->query('back'))
                        href="{{ request()->query('back') }}"
                    @elseif (is_string($back))
                        href="{{ $back }}"
                    @else
                        x-data
                        x-on:click.prevent="history.back()"
                    @endif
                >
                    <x-icon name="left-arrow-alt"/>
                </a>
            </div>
        @endif

        <div class="self-center grid gap-1">
            @if($attributes->get('title'))
                <div class="text-gray-800 font-bold truncate {{ $attributes->has('small') ? 'text-lg font-semibold' : 'text-xl font-bold' }}">
                    {{ $attributes->get('title') }}
                </div>
            @endif
    
            @if($attributes->get('subtitle'))
                <div class="text-gray-600 font-light text-sm">
                    {{ $attributes->get('subtitle') }}
                </div>
            @endif
        </div>
    </div>

    <div class="flex-shrink-0 my-1">
        {{ $slot }}
    </div>
</div>
