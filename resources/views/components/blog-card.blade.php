@php
$size = $attributes->size();
$blog = $attributes->get('blog');

if (is_numeric($blog)) $blog = model('blog')->find($blog);
elseif (is_string($blog)) $blog = model('blog')->where('slug', $blog)->first();

$desc = $blog->description ?? $blog->content;
@endphp

@if ($blog && $size === 'sm')
    <x-box :href="route('web.blog', $blog->slug)">
        <div class="group flex items-center w-full h-full min-h-28">
            <figure class="shrink-0 w-3/12 h-full bg-gray-200 flex rounded-l-md overflow-hidden opacity-70 group-hover:opacity-100 transition-opacity duration-200">
                @if ($blog->cover) <x-image :file="$blog->cover" cover/>
                @else <x-icon name="image" class="m-auto text-gray-400"/>
                @endif
            </figure>

            <div class="grow flex flex-col p-4">
                <div class="font-bold">
                    {!! $blog->name !!}
                </div>

                @if ($desc)
                    <div class="text-sm text-gray-400 font-medium">
                        {!! format($desc)->excerpt(10) !!}
                    </div>
                @endif
            </div>        
        </div>
    </x-box>
@elseif ($blog)
    <x-box :href="route('web.blog', $blog->slug)">
        <x-slot:figure>
            @if ($cover = $blog->cover)
                <img src="{{ $cover->endpoint_sm }}" class="w-full h-full object-cover">
            @endif
        </x-slot:figure>

        <div class="p-4 flex flex-col gap-2">
            <div class="text-lg font-bold text-gray-800">
                {!! $blog->name !!}
            </div>

            @if ($desc)
                <div class="text-sm text-gray-400 font-medium">
                    {!! format($desc)->excerpt(20) !!}
                </div>
            @endif
        </div>
    </x-box>
@endif
