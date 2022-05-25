@if ($attributes->get('size') === 'sm')
    <a href="{{ $attributes->get('href') }}" class="grid gap-1 bg-white border p-4 rounded-md shadow text-gray-800">
        <div class="font-bold truncate">
            {{ $attributes->get('title') }}
        </div>

        <div class="text-gray-400 text-sm font-medium">
            {{ $attributes->get('excerpt') }}
        </div>

        <div class="text-gray-500 text-sm">
            {{ format_date($attributes->get('date')) }}
        </div>
    </a>

@else
    <a href="{{ $attributes->get('href') }}" class="text-gray-800">
        <div class="bg-white rounded-md shadow overflow-hidden transition-all hover:shadow-lg">
            <div class="relative pt-[60%] bg-gray-200 overflow-hidden">
                @if ($cover = $attributes->get('cover'))
                    <figure class="absolute inset-0">
                        <img
                            src="{{ $attributes->get('cover') }}"
                            alt="{{ $attributes->get('title') }}"
                            class="w-full h-full object-cover transition-all duration-500 hover:transform hover:scale-125"
                        >
                    </figure>
                @endif
            </div>

            <div class="p-4 border border-t-0 rounded-b-md">
                <div class="text-lg font-bold truncate mb-2">
                    {{ $attributes->get('title') }}
                </div>
                <div class="text-sm text-gray-400">
                    {{ str()->limit($attributes->get('excerpt'), 100) }}
                </div>
            </div>
        </div>
    </a>
@endif

