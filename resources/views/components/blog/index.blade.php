<div class="grid gap-12">
    <div class="grid gap-2">
        <h1 class="text-4xl font-extrabold">
            {{ $attributes->get('title') }}
        </h1>

        @if ($date = $attributes->get('posted-at'))
            <div class="text-gray-500 font-medium">{{ __('Posted on ') }} {{ format_date($date) }}</div>
        @elseif ($date = $attributes->get('published-at'))
            <div class="text-gray-500 font-medium">{{ __('Published on ') }} {{ format_date($date) }}</div>
        @endif

        @if ($date = $attributes->get('updated-at'))
            <div class="text-gray-500 font-medium">{{ __('Updated on ') }} {{ format_date($date) }}</div>
        @endif

        <div>
            <x-social-share :title="$attributes->get('title')" :url="url()->current()"/>
        </div>
    </div>

    <div class="grid gap-10">
        @if ($cover = $attributes->get('cover'))
            <div class="flex">
                <figure class="m-auto bg-gray-100 rounded-md drop-shadow relative overflow-hidden">
                    <img src="{{ $cover->url }}" 
                        width="500" 
                        height="500" 
                        alt="{{ $attributes->get('title') }}"
                        class="w-full md:w-auto"
                    >
                </figure>
            </div>
        @endif

        <div class="{{ $attributes->get('class', 'prose lg:prose-lg max-w-none') }}">
            {{ $slot }}
        </div>
    </div>
</div>
