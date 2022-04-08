<main class="min-h-screen">    
    <div class="max-w-screen-xl mx-auto grid gap-12 px-4 py-20">
        {{-- header --}}
        @if ($this->blog)
            <div class="grid gap-2">
                <h1 class="text-4xl font-extrabold">
                    {{ $this->blog->title }}
                </h1>
    
                <div class="text-gray-500 font-medium">
                    Posted {{ format_date($this->blog->published_at) }}
                </div>
    
                <div>
                    <x-builder.share :title="$this->blog->title" :url="url()->current()"/>
                </div>
            </div>
        @else
            <div class="flex flex-wrap items-end justify-between gap-4">
                <h1 class="text-5xl font-extrabold flex-shrink-0">
                    {{ $this->title ?? $title ?? 'Blogs' }}
                </h1>
                <div class="max-w-md">
                    <x-input.search wire:model.debounce.500ms="search" placeholder="Search {{ str($this->title ?? $title ?? 'Blogs')->plural() }}"/>
                </div>
            </div>
        @endif
    
        <div class="grid gap-6 md:grid-cols-12">
            @if ($this->blog)
                <div class="md:col-span-9">
                    <div class="grid gap-10">
                        @if ($this->blog->cover)
                            <figure class="max-w-screen-md w-full bg-gray-100 rounded-md drop-shadow relative overflow-hidden">
                                <img src="{{ $this->blog->cover->getUrl() }}" width="500" height="500" alt="{{ $this->blog->title }}" class="w-full">
                            </figure>
                        @endif
        
                        <div class="prose prose-sm md:prose-base lg:prose-lg max-w-none">
                            {!! $this->blog->content !!}
                        </div>
                    </div>
                </div>
            @else
                <div class="{{ $this->showSidebar ? 'md:col-span-9' : 'md:col-span-12' }}">
                    <div class="grid gap-10">
                        <div class="grid gap-6 md:grid-cols-3">
                            @forelse ($this->blogs as $blog)
                                <a href="{{ route('page', ['blog/'.$blog->slug]) }}" class="text-gray-800">
                                    <x-card :image="$blog->cover->getUrl() ?? null" :alt="$blog->cover->data->alt ?? $blog->title">
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
                                    <x-empty-state 
                                        icon="news" 
                                        title="No {{ str($this->title ?? $title ?? 'blogs')->plural()->lower() }} found"
                                        subtitle=""
                                    />
                                </div>
                            @endforelse
                        </div>
        
                        {{ $this->blogs->links() }}
                    </div>
                </div>
            @endif
    
            @if ($this->showSidebar)
                <div class="md:col-span-3">
                    <div class="grid gap-10">
                        @if ($this->recents && $this->recents->count())
                            <div class="grid gap-4">
                                <div class="text-sm font-semibold text-gray-400 uppercase">
                                    Most Recent
                                </div>
    
                                @foreach ($this->recents as $blog)
                                    <a 
                                        href="{{ route('page', ['blog/'.$blog->slug]) }}" 
                                        class="grid gap-1 bg-white border p-4 rounded-md drop-shadow text-gray-800"
                                    >
                                        <div class="font-bold truncate">
                                            {{ $blog->title }}
                                        </div>
    
                                        <div class="text-gray-400 text-sm font-medium">
                                            {{ html_excerpt($blog->excerpt ?? $blog->content) }}
                                        </div>
    
                                        <div class="text-gray-500 text-sm">
                                            {{ format_date($blog->published_at) }}
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
    
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
                    </div>
                </div>
            @endif
        </div>
    </div>
</main>
