<main class="min-h-screen">    
    <div class="max-w-screen-xl mx-auto grid gap-12 px-4 py-20">
        <div class="grid gap-6 md:grid-cols-12">
            <div class="md:col-span-9">
                @if ($this->blog)
                    <x-blog
                        :title="$this->blog->title"
                        :posted-at="$this->blog->published_at"
                        :cover="$this->blog->cover"
                    >
                        {!! $this->blog->content !!}
                    </x-blog>
                @else
                    <div class="grid gap-10">
                        <h1 class="text-5xl font-extrabold flex-shrink-0">
                            {{ $this->title ?? $title ?? 'Blogs' }}
                        </h1>

                        <div class="grid gap-10">
                            <div class="grid gap-6 md:grid-cols-3">
                                @forelse ($this->blogs as $blog)
                                    <x-blog.card
                                        :href="route('page', ['blog/'.$blog->slug])"
                                        :cover="optional($blog->cover)->url"
                                        :title="$blog->title"
                                        :excerpt="html_excerpt($blog->excerpt ?? $blog->content)"
                                    />
                                @empty
                                    <div class="md:col-span-3">
                                        <x-empty-state icon="news" title="No articles found" subtitle=""/>
                                    </div>
                                @endforelse
                            </div>
            
                            {{ $this->blogs->links() }}
                        </div>
                    </div>
                @endif
            </div>

            <div class="md:col-span-3">
                <div class="grid gap-10">
                    <div class="max-w-md">
                        <x-form.search placeholder="Search Articles"/>
                    </div>

                    @if ($this->labels->count())
                        <div class="grid gap-4">
                            <div class="text-sm font-semibold text-gray-400 uppercase">
                                Topics
                            </div>

                            <div class="grid gap-1">
                                @foreach ($this->labels as $label)
                                    <div>
                                        <a 
                                            wire:click="toggleFilter('{{ $label->slug }}')"
                                            class="
                                                inline-block py-1.5 px-3 rounded-md font-medium border-2
                                                {{ collect($filters)->contains($label->slug) 
                                                    ? 'border-theme bg-theme text-white' 
                                                    : 'border-gray-400 bg-white text-gray-500' }}
                                            "
                                        >
                                            <div class="flex items-center gap-2">
                                                {{ $label->name }}
                                                @if (collect($filters)->contains($label->slug)) <x-icon name="x"/> @endif
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($this->recents->count())
                        <div class="grid gap-4">
                            <div class="text-sm font-semibold text-gray-400 uppercase">
                                Most Recent
                            </div>
            
                            @foreach ($this->recents as $blog)
                                <x-blog.card size="sm"
                                    :href="route('page', ['blog/'.$blog->slug])"
                                    :title="$blog->title"
                                    :excerpt="html_excerpt($blog->excerpt ?? $blog->content)"
                                    :date="$blog->published_at"
                                />
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
