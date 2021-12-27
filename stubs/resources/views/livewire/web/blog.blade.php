<div class="max-w-screen-xl mx-auto grid gap-12 px-4 py-20">
    {{-- header --}}
    @if ($blog)
        <div class="grid gap-2">
            <h1 class="text-4xl font-extrabold">
                {{ $blog->title }}
            </h1>
            <div class="text-gray-500 text-sm font-medium">
                Posted {{ format_date($blog->published_at) }}
            </div>
        </div>
    @else
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-7xl font-extrabold flex-shrink-0">
                Blogs
            </h1>
            <div class="max-w-md">
                <x-input.search wire:model.debounce.500ms="search" placeholder="Search Blog"/>
            </div>
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-12">
        @if ($blog)
            <div class="md:col-span-8">
                <div class="grid gap-10">
                    @if ($blog->cover)
                        <figure class="max-w-screen-md w-full bg-gray-100 rounded-md drop-shadow relative overflow-hidden">
                            <img src="{{ $blog->cover->url }}" width="500" height="500" alt="{{ $blog->name }}" class="w-full">
                        </figure>
                    @endif
    
                    <div class="prose prose-sm">{!! $blog->content !!}</div>
                </div>
            </div>
        @else
            <div class="{{ $sidebar ? 'md:col-span-8' : 'md:col-span-12' }}">
                <div class="grid gap-10">
                    <div class="grid gap-6 {{ $sidebar ? 'md:grid-cols-2' : 'md:grid-cols-3' }}">
                        @forelse ($blogs as $blog)
                            <a href="{{ route('blog.show', [$blog->slug]) }}" class="text-gray-800">
                                <x-card :image="$blog->cover->url ?? null" :alt="$blog->cover->data->alt ?? $blog->title">
                                    <div class="p-4">
                                        <div class="text-lg font-bold truncate mb-2">
                                            {{ $blog->title }}
                                        </div>
                                        <div class="text-sm text-gray-400">
                                            {{ html_excerpt($blog->excerpt ?? $blog->content) }}
                                        </div>
                                    </div>
                                </x-card>
                            </a>
                        @empty
                            <div class="md:col-span-3">
                                <x-empty-state icon="news"/>
                            </div>
                        @endforelse
                    </div>
    
                    {{ $blogs->links() }}
                </div>
            </div>
        @endif

        @if ($sidebar)
            <div class="md:col-span-4">
                <div class="grid gap-10">
                    @if ($recents && $recents->count())
                        <div class="grid gap-4">
                            <div class="text-sm font-semibold text-gray-400 uppercase">
                                Most Recent
                            </div>

                            @foreach ($recents as $blog)
                                <a href="{{ route('blog.show', [$blog->slug]) }}" class="text-gray-800">
                                    <div class="font-bold mb-2 truncate">
                                        {{ $blog->title }}
                                    </div>

                                    <div class="text-gray-400 text-sm font-medium mb-1.5">
                                        {{ html_excerpt($blog->excerpt ?? $blog->content) }}
                                    </div>

                                    <div class="text-gray-500 text-xs">
                                        {{ format_date($blog->published_at) }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    @if ($labels->count())
                        <div class="grid gap-4">
                            <div class="text-sm font-semibold text-gray-400 uppercase">
                                Topics
                            </div>

                            <div class="flex flex-wrap items-center">
                                @if ($this->label)
                                    <a 
                                        href="{{ route('blog.show') }}"
                                        class="py-1.5 px-3 border-2 border-theme bg-theme rounded-md text-white text-sm font-medium mr-3 my-1.5 flex items-center space-x-2"
                                    >
                                        <div>
                                            {{ $this->label->name }}
                                        </div>
                                        <x-icon name="x"/>
                                    </a>
                                @endif 
            
                                @foreach ($labels as $label)
                                    <a 
                                        href="{{ route('blog.show', [$label->slug]) }}"
                                        class="py-1.5 px-3 border-2 border-gray-400 rounded-md text-gray-500 text-sm font-medium mr-3 my-1.5"
                                    >
                                        {{ $label->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>