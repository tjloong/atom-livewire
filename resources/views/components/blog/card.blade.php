@props([
    'size' => $attributes->get('size'),
    'href' => $attributes->get('href'),
    'cover' => $attributes->get('cover'),
    'title' => $attributes->get('title'),
    'excerpt' => $attributes->get('excerpt'),
    'date' => $attributes->get('date'),
])

@if ($size === 'sm')
    <a href="{{ $href }}" class="grid gap-1 bg-white border p-4 rounded-md shadow text-gray-800">
        <div class="font-bold truncate">
            {{ $excerpt }}
        </div>

        <div class="text-gray-400 text-sm font-medium">
            {{ $excerpt }}
        </div>

        <div class="text-gray-500 text-sm">
            {{ format_date($date) }}
        </div>
    </a>
@else
    <div class="bg-white rounded-xl shadow overflow-hidden transition-all flex flex-col divide-y hover:shadow-lg">
        <a href="{{ $href }}" class="bg-gray-200 overflow-hidden relative pt-[60%]">
            @if ($cover)
                <figure class="absolute inset-0">
                    <img
                        src="{{ $cover }}"
                        alt="{{ $title }}"
                        class="w-full h-full object-cover transition-all duration-500 hover:transform hover:scale-125"
                    >
                </figure>
            @endif
        </a>

        <a class="p-4 bg-white grid gap-2">
            <div class="text-lg font-bold truncate text-gray-800">
                {{ $title }}
            </div>
            <div class="text-sm text-gray-400 font-medium">
                {!! str($excerpt)->limit(100) !!}
            </div>
        </a>
    </div>
@endif

