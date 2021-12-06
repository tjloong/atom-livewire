<div class="max-w-screen-xl mx-auto px-4 py-20">
    <div class="mb-14">
        @if ($blog)
            <h1 class="text-4xl font-extrabold">
                {{ $blog->title }}
            </h1>
            <div class="text-gray-500 text-sm font-medium">
                Posted {{ format_date($blog->published_at) }}
            </div>
        @else
            <div class="flex flex-wrap items-end justify-between space-y-4">
                <h1 class="text-7xl font-extrabold flex-shrink-0">
                    Blogs
                </h1>

                <div
                    x-data 
                    class="relative bg-gray-100 rounded-md flex items-center space-x-2 px-4 py-2 drop-shadow"
                >
                    <x-icon name="search" class="text-gray-400"/>
                    <input
                        x-ref="input"
                        wire:model.debounce.500ms="search"
                        type="text"
                        class="appearance-none p-0 border-0 bg-transparent focus:ring-0"
                        placeholder="Search Blogs"
                    >
                    <a
                        x-show="$refs.input.value"
                        x-on:click.prevent="$wire.set('search', null)"
                        class="text-gray-500 flex items-center justify-center"
                    >
                        <x-icon name="x"/>
                    </a>
                </div>
            </div>
        @endif
    </div>

    <div class="grid gap-6 md:grid-cols-12">
        @if ($blog)
            <div class="md:col-span-8">
                <div class="prose prose-sm">
                    {!! $blog->content !!}
                </div>
            </div>

        @else
            <div class="order-last md:col-span-8 md:order-first">
                <div class="grid gap-6 mb-10 md:grid-cols-2">
                    @forelse ($blogs as $blog)
                        <a href="{{ route('blog.show', [$blog->slug]) }}" class="text-gray-800">
                            <x-card :image="$blog->cover->url ?? null" :alt="$blog->cover->data->alt ?? $blog->title">
                                <div class="p-4">
                                    <div class="text-lg font-bold truncate mb-2">
                                        {{ $blog->title }}
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        {{ $blog->excerpt }}
                                    </div>
                                </div>
                            </x-card>
                        </a>
                    @empty
                        <div class="md:col-span-2">
                            <x-empty-state icon="news"/>
                        </div>
                    @endforelse
                </div>

                {{ $blogs->links() }}
            </div>
        @endif

        <div class="flex flex-col space-y-10 md:col-span-4">
            @if ($recents && $recents->count())
                <div class="flex flex-col space-y-4">
                    <div class="text-sm font-semibold text-gray-400 uppercase">
                        Most Recent
                    </div>

                    @foreach ($recents as $blog)
                        <a href="{{ route('blog.show', [$blog->slug]) }}" class="text-gray-800">
                            <div class="font-bold mb-2 truncate">
                                {{ $blog->title }}
                            </div>

                            <div class="text-gray-400 text-sm font-medium mb-1.5">
                                {{ $blog->excerpt }}
                            </div>

                            <div class="text-gray-500 text-xs">
                                {{ format_date($blog->published_at) }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="flex flex-col space-y-4">
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
        </div>
    </div>
</div>